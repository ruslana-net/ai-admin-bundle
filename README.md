# AiAdminBundle

## 1. Install

Clone to /path/you/project/src/Ai/AdminBundle

### Add to file composer.json

```json
"sonata-project/admin-bundle": "*",
"sonata-project/doctrine-orm-admin-bundle": "*",
"sonata-project/user-bundle": "*",
"stof/doctrine-extensions-bundle": "v1.1.0",
"pixassociates/sortable-behavior-bundle": "0.1.*@dev",
"avalanche123/imagine-bundle": "v2.1"
```

### Bash command
```sh
cd /path/to/project/
php composer.phar update
```

### Add to file app/AppKernel.php
```php
public function registerBundles()
{
    $bundles = array(
        ...,
        new Sonata\AdminBundle\SonataAdminBundle(),
        new Sonata\BlockBundle\SonataBlockBundle(),
        new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new Sonata\CoreBundle\SonataCoreBundle(),
        new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
        new FOS\UserBundle\FOSUserBundle(),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new Pix\SortableBehaviorBundle\PixSortableBehaviorBundle(),
        new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
        new Ai\AdminBundle\AiAdminBundle(),
        ...
    );
    ...
}
```

## 2. CONFIGURATION

### Add to files app/config/parameters.yml AND ./parameters.yml.dist
```yml
dashboard_title: Dashboard title
project_name: Project name
```

### Add to file app/config/config.yml
```yml
#Doctrine extentions
stof_doctrine_extensions:
    default_locale: %locale%
    translation_fallback: true
    orm:
        default:
          sluggable: true
          timestampable: true
          sortable: true

#Fos User Bundle
fos_user:
    db_driver: orm
    firewall_name: admin
    user_class: Ai\AdminBundle\Entity\BeUser
    group:
        group_class: Ai\AdminBundle\Entity\BeGroup

#Sonata
sonata_admin:
    title: "%dashboard_title%"
    options:
            html5_validate: true
            confirm_exit: true
            use_select2: true
    templates:
        layout: AiAdminBundle:Admin:standard_layout.html.twig
    dashboard:
        groups:
#            sonata_app_recipes:
#                label: Рецепты:
#                label_catalogue: ~:
#                items:
#                    - app.admin.categories
#                roles: [ROLE_SUPER_ADMIN, ROLE_SONATA_ADMIN, ROLE_MANAGER]
            ai_admins:
                label: Администрирование:
                items:
                    - ai_admin.be_user
                    - ai_admin.be_group
                roles: [ROLE_SUPER_ADMIN]
        blocks:
#            -
#                position: left
#                type: sonata.admin.block.admin_list
#                settings:
#                    groups: [sonata_app_recipes]
            -
                position: right
                type: sonata.admin.block.admin_list
                settings:
                    groups: [ai_admins]

sonata_block:
    default_contexts: [sonata_page_bundle]
    blocks:
        sonata.admin.block.admin_list:
            contexts:   [admin]

sonata_doctrine_orm_admin:
    entity_manager: ~
    templates:
        form:
            - AiAdminBundle:Admin:form_admin_fields.html.twig


pix_sortable_behavior:
    db_driver: orm
    position_field:
        default: position

avalanche_imagine:
    filters:
        admin_thumb:
            type:    thumbnail
            options: { size: [120, 120], mode: outbound }
```

### Add to file app/config/security.yml
```yml
security:
    providers:
        fos_userbundle:
            id: fos_user.user_manager
        be_user:
            entity: { class: Ai\AdminBundle\Entity\BeUser, property: username }
    encoders:
        FOS\UserBundle\Model\UserInterface: sha512
        Ai\AdminBundle\Entity\BeUser: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_SONATA_ADMIN
        ROLE_SUPER_ADMIN: ROLE_SUPER_ADMIN
        ROLE_MANAGER: ROLE_MANAGER
        ROLE_DEFAULT: ROLE_USER

    firewalls:
        admin:
            pattern:      /admin(.*)
            form_login:
                provider:       be_user
                login_path:     /admin/login
                use_forward:    false
                check_path:     /admin/login_check
                failure_path:   null
                default_target_path: /admin/dashboard
                always_use_default_target_path: true
            logout:
                path:           /admin/logout
            anonymous:    true
#        main:
#            pattern: ^/
#            form_login:
#                provider: fos_userbundle
#                csrf_provider: form.csrf_provider
#            logout:       true
#            anonymous:    true

    access_control:
        # URL of FOSUserBundle which need to be available to anonymous users
        - { path: ^/_wdt, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/_profiler, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }

        # -> custom access control for the admin area of the URL
        - { path: ^/admin/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/logout$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/login-check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        # -> end

        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }

        # Secured part of the site
        # This config requires being logged for the whole site and having the admin role for the admin part.
        # Change these rules to adapt them to your needs
        - { path: ^/admin, role: [ROLE_MANAGER, ROLE_SONATA_ADMIN, ROLE_SUPER_ADMIN] }
        - { path: ^/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

###Add to file app/config/routing.yml
```yml
...
ai_admin:
    resource: "@AiAdminBundle/Resources/config/routing.yml"
    prefix:   /admin
...
```

## 3. Run
```bash
php app/console doctrine:schema:create
php app/console doctrine:schema:update --force
php app/console assets:install web
php app/console assetic:dump
php app/console cache:clear 
```

## 4. Add admin users
```bash
php app/console fos:user:create
```