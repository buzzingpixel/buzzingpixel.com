---
title: How To Use Construct
slug: how-to-use-construct
date: 2016-01-20 01:00 PM
---
Whether you just want to create user-friendly, drag and drop menus, or you would like to create menus with associated Channel Entries, Construct is a great tool to have in your tool belt. In this tutorial, I’d like to show you how to use some of the features of Construct.

Let's first take a look at how to create menus with Construct.

## Menus in the Control Panel

If you have a [fresh installation of Construct][Construct installation docs], you will need to create one or more trees for your nodes (navigation items) to live in. If you have more than one menu on your site — say very different menus for the header and footer — you might want to create two trees such as Header and Footer.

[Construct installation docs]: /software/construct/documentation

When creating trees, you can assign which member groups will have access to those trees. Non-Super Admins will only see the trees you give them access to and will not have access to any other settings. This makes Construct a great tool for separating responsibilities among content editors and site managers as needed.

Once you have created some trees, you are ready to add nodes to that tree. When using Construct for only menu creation (meaning you have routing turned off in settings), the Node options are very simple.

<p class="centered">
    <img src="/uploads-static/news/2016-01-20-how-to-use-construct/create-node.png" alt="Create Node">
</p>

After creating a node you can drag and drop nodes to nest them as needed (within the neseting limits you set when creating the tree). Construct uses the node's slug to build out the full URL path to that node. So for instance, if you have the node `our-ceo` nested under `our-company`, which in turn is nested under `about`, which is at the root level, the `our-ceo` node’s full URL path would be `about/our-company/our-ceo`.

<p class="centered">
    <img src="/uploads-static/news/2016-01-20-how-to-use-construct/nodes.png" alt="Nodes">
</p>

This makes it extremely easy for site managers to manage the menu structure without having to type out paths with the added possibility of making mistakes.

But not all sites are the same; they don't all conform to a specific structure and may need to link to external URLs in the menu, or a different location in the hierarchy. Construct 2 accounts for that with the external URL field. You can link to anything you need to from any node.

## Menu Output in Templates

Now that we have a tree or two with nodes in them, we need to output those nodes for the menus. Fortunately Construct [makes this pretty easy][construct tags]. Here is an example of simple menu output:

<div class="content-blocks__pre-wrapper content-blocks__pre-wrapper--example">
<pre class="content-blocks__pre content-blocks__pre--example language-ee">
<code class="content-blocks__code content-blocks__code--example language-ee">
&lt;nav class="site-nav">
    {exp:construct:nodes tree_id="2"}
        {if construct:level_count == 1}
        &lt;ul class="site-nav__list">
        {/if}
            &lt;li class="site-nav__item">
                &lt;a href="{construct:node_link}" class="site-nav__link">
                    {construct:node_name}
                &lt;/a>
                {construct:children}
            &lt;/li>
        {if construct:level_count == construct:level_total_results}
        &lt;/ul>
        {/if}
    {/exp:construct:nodes}
&lt;/nav>
</code>
</pre>
</div>

[construct tags]: /software/construct/documentation/template-tags

So let’s deconstruct what's going on there. It’s all pretty easy to grasp if you are used to working with EE templates.

All the nodes are going to use the same markup from our tag pair. If it is the first item for the current level (`{if construct:level_count == 1}`), we're going to start a new unordered list. If it is the last item for the current level(`{if construct:level_count == construct:level_total_results}`), we're going to end the unordered list.

We're using the `{construct:node_link}` tag which will output a link to the current node hierarchy, or the external link if present. And, of course, the node name.

Here is where things get more interesting. There's a strange looking tag in there: `{construct:children}`. Think of this tag as a marker. This is where any children of that node are placed. The children are rendered using the same markup, but instead of just being placed next after the parent node's output, they are placed where the marker tag is at. This allows for the possibility of nesting while using the same markup for all your nodes and keeping things really DRY.

But again, not all sites are the same and you may have a need to use different markup for a specific node level. Luckily for us, the ExpressionEngine template parser will let us do what we need:

<div class="content-blocks__pre-wrapper content-blocks__pre-wrapper--example">
<pre class="content-blocks__pre content-blocks__pre--example language-ee">
<code class="content-blocks__code content-blocks__code--example language-ee">
&lt;nav class="site-nav">
    {exp:construct:nodes tree_id="2" max_depth="2"}
        {if construct:node_level == 1}
            {if construct:level_count == 1}
            &lt;ul class="site-nav__list">
            {/if}
                &lt;li class="site-nav__item">
                    &lt;a href="{construct:node_link}" class="site-nav__link">
                        {construct:node_name}
                    &lt;/a>
                    {construct:children}
                &lt;/li>
            {if construct:level_count == construct:level_total_results}
            &lt;ul>
            {/if}
        {if:elseif construct:node_level == 2}
            &lt;div class="site-nav__sub-nav-panel">
                &lt;a href="{construct:node_link}" class="site-nav__sub-nav-link">
                    {construct:node_name}
                &lt;/a>
            &lt;/div>
        {/if}
    {/exp:construct:nodes}
&lt;/nav>
</code>
</pre>
</div>

So in this case we're using entirely different markup for the second level nodes, which are being placed where the `{construct:children}` tag is at for their parent. This is just the simplest of examples, but I'm sure you can see just how powerful that is.

## Creating Pages

Construct can also create pages for the menu items. To do this, you will need to create template preferences in the Construct Control panel. Each template preference has a few options: a name that you want the content editors to see when selecting the template, the EE template, channels for the template, and some listing options.

<p class="centered">
    <img src="/uploads-static/news/2016-01-20-how-to-use-construct/standard-page-template.png" alt="Standard Page Template">
</p>

After you have created some templates, you also need to add them to any trees you want them to be available to. After you have done that, the template, and any entries from the channels you chose for that template, will be available to select for each node.

<p class="centered">
    <img src="/uploads-static/news/2016-01-20-how-to-use-construct/creating-a-page.png" alt="Creating a page">
</p>

Once a template and entry are selected for a node, when you visit that Node's URI, Construct will serve that template and make [certain variables][construct route variables] available to the template so you can hook up the Channel Entries tag. Here is a very simple example:

[construct route variables]: /software/construct/documentation/routing#route-variables

<div class="content-blocks__pre-wrapper content-blocks__pre-wrapper--example">
<pre class="content-blocks__pre content-blocks__pre--example language-ee">
<code class="content-blocks__code content-blocks__code--example language-ee">
{exp:channel:entries
    disable="categories|member_data|pagination"
    dynamic="no"
    entry_id="{construct_route:node_entry_id}"
    limit="1"
    status="open"
}
    &lt;h1>{title}&lt;/h1>
    {body}
{/exp:channel:entries}
</code>
</pre>
</div>

## Entry Listing (such as a blog)

Construct can also make it easy to create entry listing and single entry pages. When creating template preferences, you can choose to make channels available to that template for entry listing, or you can define a template as a single entry template:

<p class="centered">
    <img src="/uploads-static/news/2016-01-20-how-to-use-construct/blog-index-template.png" alt="blog index template">
</p>

<p class="centered">
    <img src="/uploads-static/news/2016-01-20-how-to-use-construct/blog-entry-template.png" alt="blog entry template">
</p>

You can see in the first screen shot that I have created a blog entry template, and in the second I have created a blog single entry template. Now when I choose the blog index as the template for a node, it will also give me the option to chose any of the listing channels for that template, and to choose a single entry template for those entries.

Here is what my node settings look like:

<p class="centered">
    <img src="/uploads-static/news/2016-01-20-how-to-use-construct/blog-node.png" alt="blog node">
</p>

Now visiting that node’s URI (along with a pagination segment if present since I selected pagination for that node), Construct will serve the blog index template, and `node/uri/some_segment` will serve the blog entry template. Now let's look at the code that powers those two templates.

### Blog Index Template

<div class="content-blocks__pre-wrapper content-blocks__pre-wrapper--example">
<pre class="content-blocks__pre content-blocks__pre--example language-ee">
<code class="content-blocks__code content-blocks__code--example language-ee">
{exp:channel:entries
    channel="{construct_route:node_listing_channels}"
    {if construct_route:node_pagination}
    disable="categories|member_data"
    limit="{construct_route:node_pagination_amount}"
    {if:else}
    disable="categories|member_data|pagination"
    limit="6"
    {/if}
}
    {if no_results}{redirect="404"}{/if}
    &lt;h2>&lt;a href="/{construct_route:node_full_route}/{url_title}">{title}&lt;/a>&lt;/h2>
    {body}
    &lt;hr>
    {if construct_route:node_pagination}
        {paginate}
            &lt;p>Page {current_page} of {total_pages} pages {pagination_links}&lt;/p>
        {/paginate}
    {/if}
{/exp:channel:entries}
</code>
</pre>
</div>

For the index template, I’m feeding the channel entries tag the `{construct_route:node_listing_channels}` variable, and I'm checking on whether pagination has been enabled to do various things.

### Blog Entry Template

<div class="content-blocks__pre-wrapper content-blocks__pre-wrapper--example">
<pre class="content-blocks__pre content-blocks__pre--example language-ee">
<code class="content-blocks__code content-blocks__code--example language-ee">
{exp:channel:entries
    channel="{construct_route:node_listing_channels}"
    disable="categories|member_data|pagination"
    limit="1"
    require_entry="yes"
    url_title="{last_segment}"
    dynamic="no"
}
    {if no_results}{redirect="404"}{/if}
    &lt;h2>{title}&lt;/h2>
    {body}
{/exp:channel:entries}
</code>
</pre>
</div>

There are very few surprises here. I’m setting `require_entry="yes"` so that invalid uri_titles will trigger the 404, I’m disabling dynamic `url_title` detection and feeding the slug manually so that no matter how much nesting may be happening, EE will not get confused. And that's pretty much all there is to it.

## Simple and Powerful

So as you can see, Construct is easy to use and a powerful tool for creating menus, pages, listing content and more. If you would like to give Construct a try, I’d love to let you kick the tires! Just [get in touch with me][contact]. And if you do find that it is a good fit (and I think you will, of course), then you can [head on over to devot:ee][Construct on devot:ee] and purchase a site license. Happy developing!

[Construct on devot:ee]: https://devot-ee.com/add-ons/construct
[contact]: /contact
