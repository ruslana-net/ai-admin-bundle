<?php

namespace Ai\AdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\Collection;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;

class AdminController extends CRUDController
{
    /**
     * Move element
     *
     * @param integer $id
     * @param string $position
     */
    public function moveAction($id, $position)
    {
        $id     = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        $position_service = $this->get('pix_sortable_behavior.position');
        $last_position = $position_service->getLastPosition(get_class($object));
        $position = $position_service->getPosition($object, $position, $last_position);

        $object->setPosition($position);
        $this->admin->update($object);

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
                'objectId' => $this->admin->getNormalizedIdentifier($object)
            ));
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->set('sonata_flash_info', $translator->trans('Position updated'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionSendSms(ProxyQueryInterface $query)
    {
        if (false === $this->admin->isGranted('SEND_SMS')) {
            throw new AccessDeniedException();
        }

        $translator = $this->get('translator');

        if ( !$smsText = $this->get('request')->get('sms_text') )
        {
            $this->get('session')->getFlashBag()->set('sonata_flash_error', $translator->trans('Sms text is empty!'));
        } elseif ( count($this->get('request')->get('idx')) == 0 )
        {
            $this->get('session')->getFlashBag()->set('sonata_flash_error', $translator->trans('Elements is not select!'));
        } else {
            $this->get('session')->getFlashBag()->set('sonata_flash_success', $translator->trans('SMS send successful!'));
        }

        return $this->redirectToRoute('sonata_admin_dashboard');
    }
}
