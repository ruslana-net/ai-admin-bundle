# AiAdminBundle

## 1. Install

### Add to file composer.json

```json
"require": {
    ...
    "ai/admin-bundle": "dev-master"
    ...
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/ruslana-net/ai-admin-bundle"
    }
],
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
        new Sonata\UserBundle\SonataUserBundle(),
        new Sonata\CacheBundle\SonataCacheBundle(),
        new FOS\UserBundle\FOSUserBundle(),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new Pix\SortableBehaviorBundle\PixSortableBehaviorBundle(),
        new Liip\ImagineBundle\LiipImagineBundle(),
        new Oneup\UploaderBundle\OneupUploaderBundle(),
        new Ai\AdminBundle\AiAdminBundle(),
        ...
    );
    ...
}
```

## 2. CONFIGURATION

### Add to files app/config/parameters.yml AND ./parameters.yml.dist

Check locale in config.yml

```yml
locale: ru
dashboard_title: Dashboard title
project_name: Project name
domain: domain.ru
```

### Add to file app/config/config.yml
```yml
imports:
    ...
    - { resource: bundles.yml }

framework:
    ...
    translator:      { fallbacks: ["%locale%"] }
    ...

twig:
    ...
    form_themes:
        - AiAdminBundle:Form:div_layout.html.twig
    ...
```

### Create and add to file app/config/bundles.yml
```yml
#Doctrine extentions
stof_doctrine_extensions:
    default_locale: "%locale%"
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
        use_icheck: true
    templates:
        layout: AiAdminBundle:Admin:standard_layout.html.twig
    dashboard:
        groups:
            ai_admins:
                label: Administration:
                icon: <i class="fa fa-cog"></i>
                items:
                    - ai_admin.be_user
                    - ai_admin.be_group
                roles: [ROLE_SUPER_ADMIN]
        blocks:
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
        sonata.admin.block.search_result:
            contexts: [admin]

sonata_doctrine_orm_admin:
    entity_manager: ~
    templates:
        form:
            - AiAdminBundle:Admin:form_admin_fields.html.twig

sonata_cache:
    caches:
#        esi:
#            token: an unique security key # a random one is generated by default
#            servers:
#                - varnishadm -T 127.0.0.1:2000 {{ COMMAND }} "{{ EXPRESSION }}"
#        ssi:
#            token: an unique security key # a random one is generated by default
#        mongo:
#            database:   cache
#            collection: cache
#            servers:
#                - {host: 127.0.0.1, port: 27017, user: username, password: pASS'}
#                - {host: 127.0.0.2}
        memcached:
            prefix: test     # prefix to ensure there is no clash between instances
            servers:
                - {host: 127.0.0.1, port: 11211, weight: 0}
        predis:
            servers:
                - {host: 127.0.0.1, port: 6379, database: 42}
        apc:
            token:  s3cur3   # token used to clear the related cache
            prefix: test     # prefix to ensure there is no clash between instances
            servers:
                - { domain: "%domain%", ip: 127.0.0.1, port: 80}
        symfony:
            token: s3cur3 # token used to clear the related cache
            php_cache_enabled: true # Optional (default: false), clear APC or PHP OPcache
            types: [mytype1, mycustomtype2] # Optional, you can restrict allowed cache types
            servers:
                - { domain: "%domain%", ip: 127.0.0.1, port: 80}

pix_sortable_behavior:
    db_driver: orm

liip_imagine:
    resolvers:
       default:
          web_path: ~

    filter_sets:
        cache: ~
        admin_thumb:
            quality: 100
            filters:
                thumbnail: { size: [120, 100], mode: outbound }
        be_user_avatar:
            quality: 100
            filters:
                thumbnail: { size: [250, 200], mode: outbound }
                
#File Uploader
oneup_uploader:
    orphanage:
        maxage: 86400
        directory: "%kernel.cache_dir%/uploader/orphanage"
    mappings:
        be_user_avatar:
            frontend: fineuploader
            use_orphanage: true
            route_prefix: admin
            enable_cancelation: true
            
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
_liip_imagine:
    resource: "@LiipImagineBundle/Resources/config/routing.xml"

_oneup_uploader:
    resource: .
    type: uploader
    
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
php app/console cache:clear 
```

## 4. Add admin users
```bash
php app/console fos:user:create
php app/console fos:user:promote admin ROLE_SONATA_ADMIN
php app/console fos:user:promote admin ROLE_SUPER_ADMIN
```