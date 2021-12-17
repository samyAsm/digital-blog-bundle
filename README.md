# DHI Blog bundle

This bundle is made to facilitate basic blog integration inside symfony 4 apps

It requires

"php": ">=7.2",

"ext-curl": "*",

"ext-json": "*",

"doctrine/doctrine-bundle": "^2.1",

"doctrine/orm": "^2.7",

"nelmio/api-doc-bundle": "^3.0",

"symfony/security-bundle": "4.4.*",

"symfony/swiftmailer-bundle": "^3.4",

"symfony/translation": "4.4.*",

"symfony/validator": "4.4.*",

"symfony/yaml": "4.4.*",

"twig/twig": "^2.12|^3.0"

## Installation

```bash
composer require samyasm/digitalblogbundle
```
## Configurations

### Routing

Open ```config/routes.yaml``` and add this to configure routing to blog

```
digital_blog:
  resource: '@DigitalBlogBundle/Resources/config/routes.yaml'
  prefix: /blog/ 
  #You can set any prefix you want :-)
```

### Bundle configuration

create the file ```config/packages/digital_blog.yaml``` and put this content

```
digital_blog:
  
  assets:
    logo: 'assets-front/assets/img/logo.png'
    hero_bg: 'assets-app/images/banner.jpg'
    
  theme:
    color_primary: '#7083ff'
    color_secondary: '#f5ec78'
    
  routing:
    prefix: '/v1/blog'
  
  store:
    author_image_store: 'uploads/author/'
    article_image_store: 'uploads/article/'
    category_image_store: 'uploads/category/'
```

Open the file ```config/packages/security.yaml``` and add this content

```
security:
    encoders:
        # ...
        
        #Add encoder for Author entity, considered as users in administration
        
        DhiBlogBundle\Entity\Author:
            algorithm: sha512
            cost: 12
```

Make sure to have ```Dhi\BlogBundle\DigitalBlogBundle::class => ['all' => true],``` into ```config/bundles.php``` and add this content

### Env

```
SESSION_LIFE_TIME=3600
SESSION_DIGITAL_BLOG_TOKEN=auth_blog_token

DIGITAL_BLOG_EMAIL_SENDER=support@dhi-academy.com
DIGITAL_BLOG_EMAIL_FROM=Support

DIGITAL_BLOG_ADMIN_EMAIL=example@example.com
DIGITAL_BLOG_ADMIN_PASSWORD=password
DIGITAL_BLOG_ADMIN_NAME=admin
```

### Update schema

```
php bin/console doctrine:schema:update --force
```
