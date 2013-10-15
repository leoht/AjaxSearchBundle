#Using the AjaxSearchBundle

The AjaxSearchBundle provides features to quickly implement AJAX search engines into your application.

##Getting started
###Install with composer

Add the package into your ```composer.json```:
```js
{
  "require": {
    "leoht/ajaxsearch-bundle"
  }
}
```
Then run composer :
```
php composer.phar update leoht/ajaxsearch-bundle
```
###Enable the bundle
Register now the bundle into the kernel:
```php
<?php

public function registerBundles()
{
  $bundles = array(
    //...
    new LeoHt\AjaxSearchBundle\LeoHtAjaxSearchBundle(),
  );
}
```
Install the bundle assets:
```
php app/console assets:install web --symlink
```
###Import the bundle routing
Add these lines into your routing.yml:
```yaml
leoht_ajaxsearch:
    resource: "@LeoHtAjaxSearchBundle/Controller/"
    type: annotation
    prefix: /_ajaxsearch
```
###Configure the bundle

It's now time to configure your bundle in your configuration file. At least one search engine must be configured,
but you can configure as many engines as you need. Each engine must know which entity (if you use Doctrine) or table
(if you use Propel) it has to look for and which attributes (or columns) he has to search in.

Here is a simple example of a working configuration (see below for more advanced config):
```yaml
leoht_ajaxsearch:
    engines:
        main:
            search_in
                entity: AcmeFooBundle:Post       # Entities the engine will look for
                properties: [ title, author_name ]  # Properties the engine will inspect
```

###Enable the bundle on the client side
Our bundle is using AJAX requests, so we have to do a little more work into the front-end part of the app to
make it work. First, add the following into the stylesheets and javascripts sections of your HTML code:
```html
<link rel="stylesheet" href="{{ asset('bundles/leohtajaxsearch/css/ajaxsearch.css') }}" />
//...
<script src="{{ asset('bundles/leohtajaxsearch/js/ajaxsearch.js') }}" ></script>
```
Note that the AjaxSearchBundle needs jQuery, it will be automatically added to your page if not found.

Then, include the twig template containing the search form into your own template:
```twig
<!-- Here we want our search form -->

{% include 'LeoHtAjaxSearchBundle:Search:search.html.twig' with {
    'engine': 'main'
} %}
```
This line will include the bundle template, and tells him we want this form working with the search engine "main" (which we configured above).

Finally, add a simple javascript line somewhere to enable the AJAX process:
```html
<script>
    AjaxSearch.init()
</script>
```

##More configuration

###With Propel

By default the bundle is assuming that you're using Doctrine. If you're using Propel, you must configure it
explicitely:
```yaml
leoht_ajaxsearch:
    orm: propel
    engines:
        main:
            search_in:
                table: post     # Table name instead of entity
                properties: [ title, author_name ]  # Properties the engine will inspect
```
###Choose which attributes to display
If you don't configure which search result attributes are displayed when the AJAX response is sent to the page,
the bundle will assume that you want all of the properties you use for the research to be displayed (except for the id).
You can change this behavior by telling which properties you want to display:
```yaml
leoht_ajaxsearch:
    engines:
        main:
            #...
            results:
                display: [ title ] # We only want to display the post title
```
###Provide links into search results
You can provide a link for each search result that will be fetched, so it can become an HTML link
which lead to another page (in the example above, it could be a page showing the post details).
To allow the bundle generating such a link, you must provide the appropriate route configuration:

```yaml
leoht_ajaxsearch:
    engines:
        main:
            search_in:
                entity: AcmeFooBundle:Post       # Entities the engine will look for
                properties: [ title, author_name ]  # Properties the engine will inspect
            results:
                provide_link:
                    # The route name
                    route: show_post 
                    # Here the value "id" tells the bundle to use the "id" attribute of the fetched post.
                    parameters: { id: id }   
```
###Configure multiple search engines
If you need multiple search engines (e.g one for your posts, one for your users, or whatever you want),
just add it to your configuration:
```yaml
leoht_ajaxsearch:
    engines:
        posts:
            search_in:
                entity: AcmeFooBundle:Post
                properties: [ title, author_name ] 
            results:
                provide_link: { route: show_post, parameters: { id: id } }
        users:
            search_in:
                entity: AcmeFooBundle:User
                properties: [ username, email ]
```
###Configure the search form
From the template side, you can also configure the search form, by passing parameters to the ```include``` directive.
Here is the way to do this, and the defaults values for each parameter that can be provided:
```
{% include 'LeoHtAjaxSearchBundle:Search:search.html.twig' with {
    'engine': 'main',
    'placeholder': 'Search...',
    'noresult_message': 'No result'
} %}
```
If you prefer, you can use the ```embed``` Twig directive to overwrite the default values:
```
{% embed 'LeoHtAjaxSearchBundle:Search:search.html.twig' %}
    {% block engine %}main{% endblock %}
    {% block placeholder %}Search...{% endblock %}
{% endembed %}
```
