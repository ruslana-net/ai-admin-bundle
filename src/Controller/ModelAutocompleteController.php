<?php

namespace Ai\AdminBundle\Controller;

use Symfony\Component\Validator\Constraints\Collection;
use Sonata\AdminBundle\Controller\HelperController;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Admin\AdminHelper;
use Symfony\Component\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\AdminBundle\Filter\FilterInterface;

class ModelAutocompleteController extends HelperController
{
    public function __construct(\Twig_Environment $twig, Pool $pool, AdminHelper $helper, ValidatorInterface $validator)
    {
        parent::__construct($twig, $pool, $helper, $validator);
    }

    /**
     * Retrieve list of items for autocomplete form field
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \RuntimeException
     * @throws AccessDeniedException
     */
    public function retrieveAutocompleteItemsAction(Request $request)
    {
        $admin = $this->pool->getInstance($request->get('code'));
        $admin->setRequest($request);

        if (false === $admin->isGranted('CREATE') && false === $admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }

        // subject will be empty to avoid unnecessary database requests and keep autocomplete function fast
        $admin->setSubject($admin->getNewInstance());

        $fieldDescription = $this->retrieveFieldDescription_($admin, $request->get('field'));
        $formAutocomplete = $admin->getForm()->get($fieldDescription->getName());

        if ($formAutocomplete->getConfig()->getAttribute('disabled')) {
            throw new AccessDeniedException('Autocomplete list can`t be retrieved because the form element is disabled or read_only.');
        }

        $property           = $formAutocomplete->getConfig()->getAttribute('property');
        $callback           = $formAutocomplete->getConfig()->getAttribute('callback');
        $minimumInputLength = $formAutocomplete->getConfig()->getAttribute('minimum_input_length');
        $itemsPerPage       = $formAutocomplete->getConfig()->getAttribute('items_per_page');
        $reqParamPageNumber = $formAutocomplete->getConfig()->getAttribute('req_param_name_page_number');
        $toStringCallback   = $formAutocomplete->getConfig()->getAttribute('to_string_callback');

        $searchText = $request->get('q');
        $createNew = ( $request->get('create') == 1 && $searchText != "" ) ? true : false;
        $targetAdmin = $fieldDescription->getAssociationAdmin();

        // check user permission
        if (false === $targetAdmin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        if (mb_strlen($searchText, 'UTF-8') < $minimumInputLength) {
            return new JsonResponse(array('status' => 'KO', 'message' => 'Too short search string.'), 403);
        }

        $datagrid = $targetAdmin->getDatagrid();

        if ($callback !== null) {
            if (!is_callable($callback)) {
                throw new \RuntimeException('Callback does not contain callable function.');
            }

            call_user_func($callback, $targetAdmin, $property, $searchText);
        } else {
            if (is_array($property)) {
                // multiple properties
                foreach ($property as $prop) {
                    if (!$datagrid->hasFilter($prop)) {
                        throw new \RuntimeException(sprintf('To retrieve autocomplete items, you should add filter "%s" to "%s" in configureDatagridFilters() method.', $prop, get_class($targetAdmin)));
                    }

                    $filter = $datagrid->getFilter($prop);
                    $filter->setCondition(FilterInterface::CONDITION_OR);

                    $datagrid->setValue($prop, null, $searchText);
                }
            } else {
                if (!$datagrid->hasFilter($property)) {
                    throw new \RuntimeException(sprintf('To retrieve autocomplete items, you should add filter "%s" to "%s" in configureDatagridFilters() method.', $property, get_class($targetAdmin)));
                }

                $datagrid->setValue($property, null, $searchText);
            }
        }

        $datagrid->setValue('_per_page', null, $itemsPerPage);
        $datagrid->setValue('_page', null, $request->query->get($reqParamPageNumber, 1));
        $datagrid->buildPager();

        $pager = $datagrid->getPager();

        $items = array();
        $results = $pager->getResults();

        if ( $createNew ) {
            $labelNew = $this->pool->getContainer()->get('translator')->trans('Create New');
            $items[] = array(
                'id' => $searchText,
                'label' => $searchText . " ($labelNew)",
            );
        }

        foreach ($results as $entity) {
            if ($toStringCallback !== null) {
                if (!is_callable($toStringCallback)) {
                    throw new \RuntimeException('Option "to_string_callback" does not contain callable function.');
                }

                $label = call_user_func($toStringCallback, $entity, $property);
            } else {
                $resultMetadata = $targetAdmin->getObjectMetadata($entity);
                $label = $resultMetadata->getTitle();
            }

            $items[] = array(
                'id'    => $admin->id($entity),
                'label' => $label,
            );
        }

        return new JsonResponse(array(
            'status' => 'OK',
            'more'   => $createNew ? false : !$pager->isLastPage(),
            'items'  => $items
        ));
    }

    /**
     * Retrieve the field description given by field name.
     *
     * @param AdminInterface $admin
     * @param string         $field
     *
     * @return \Symfony\Component\Form\FormInterface
     *
     * @throws \RuntimeException
     */
    private function retrieveFieldDescription_(AdminInterface $admin, $field)
    {
        $admin->getFormFieldDescriptions();

        $fieldDescription = $admin->getFormFieldDescription($field);

        if (!$fieldDescription) {
            throw new \RuntimeException(sprintf('The field "%s" does not exist.', $field));
        }

        if ($fieldDescription->getType() !== 'sonata_type_model_autocomplete'
            && $fieldDescription->getType() !== 'ai_admin_model_autocomplete') {
            throw new \RuntimeException(sprintf('Unsupported form type "%s" for field "%s".', $fieldDescription->getType(), $field));
        }

        if (null === $fieldDescription->getTargetEntity()) {
            throw new \RuntimeException(sprintf('No associated entity with field "%s".', $field));
        }

        return $fieldDescription;
    }
}