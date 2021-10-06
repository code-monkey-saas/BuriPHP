<?php
defined('_EXEC') or die;

// Pages
$this->dependencies->add(['js', '{$path.js}pages/help.js']);
?>
<main class="wrapper">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <ol class="breadcrumb hide-phone">
                        <li class="breadcrumb-item">
                            <a href="index.php">{$_webpage}</a>
                        </li>
                        <li class="breadcrumb-item active">Help</li>
                    </ol>

                    <h4 class="page-title">Tipografías</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Headings</h4>
                        <p class="text-muted m-b-30 font-14">All HTML headings, <code class="highlighter-rouge">&lt;h1&gt;</code> through <code class="highlighter-rouge">&lt;h6&gt;</code>, are available.</p>

                        <h1>h1. Heading <small>small</small></h1>
                        <h2>h2. Heading <small>small</small></h2>
                        <h3>h3. Heading <small>small</small></h3>
                        <h4>h4. Heading <small>small</small></h4>
                        <h5>h5. Heading <small>small</small></h5>
                        <h6>h6. Heading <small>small</small></h6>
                    </div>
                </div>

                <div class="card m-b-30">
                    <div class="card-body">

                        <h4 class="m-t-0 header-title">Inline text elements</h4>
                        <p class="text-muted m-b-30 font-14">Styling for common inline HTML5 elements.</p>

                        <p>You can use the mark tag to <mark>highlight</mark> text.</p>
                        <p><del>This line of text is meant to be treated as deleted text.</del></p>
                        <p><s>This line of text is meant to be treated as no longer accurate.</s></p>
                        <p><ins>This line of text is meant to be treated as an addition to the document.</ins></p>
                        <p><u>This line of text will render as underlined</u></p>
                        <p><small>This line of text is meant to be treated as fine print.</small></p>
                        <p><strong>This line rendered as bold text.</strong></p>
                        <p class="m-b-0"><em>This line rendered as italicized text.</em></p>
                    </div>
                </div>

                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Unstyled List</h4>
                        <p class="text-muted m-b-30 font-14">Remove the default <code class="highlighter-rouge">list-style</code> and left margin on list items (immediate children only). <strong>This only applies to immediate children list items</strong>, meaning you will need to add the class for any nested lists as well.</p>

                        <ul class="list-unstyled m-b-0">
                            <li>Integer molestie lorem at massa</li>
                            <li>Nulla volutpat aliquam velit
                                <ul>
                                    <li>Phasellus iaculis neque</li>
                                    <li>Purus sodales ultricies</li>
                                    <li>Vestibulum laoreet porttitor sem</li>
                                </ul>
                            </li>
                            <li>Faucibus porta lacus fringilla vel</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Display headings</h4>
                        <p class="text-muted m-b-30 font-14">Traditional heading elements are designed to work best in the meat of your page content.</p>

                        <h1 class="display-1">Display 1</h1>
                        <h1 class="display-2">Display 2</h1>
                        <h1 class="display-3">Display 3</h1>
                        <h1 class="display-4 m-b-0">Display 4</h1>
                    </div>
                </div>

                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Blockquotes</h4>
                        <p class="text-muted m-b-30 font-14">For quoting blocks of content from another source within your document. Wrap <code class="highlighter-rouge">&lt;blockquote class="blockquote"&gt;</code> around any <abbr title="HyperText Markup Language">HTML</abbr> as the quote.</p>

                        <blockquote class="blockquote font-18">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote>

                        <blockquote class="blockquote blockquote-reverse font-18 m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote>
                    </div>
                </div>

                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Inline List</h4>
                        <p class="text-muted m-b-30 font-14">Remove a list’s bullets and apply some light <code class="highlighter-rouge">margin</code> with a combination of two classes, <code class="highlighter-rouge">.list-inline</code> and <code class="highlighter-rouge">.list-inline-item</code>.</p>

                        <ul class="list-inline m-b-0">
                            <li class="list-inline-item">Lorem ipsum</li>
                            <li class="list-inline-item">Phasellus iaculis</li>
                            <li class="list-inline-item">Nulla volutpat</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Description list alignment</h4>
                        <p class="text-muted m-b-30 font-14">Align terms and descriptions horizontally by using our grid system’s predefined classes (or semantic mixins). For longer terms, you can optionally add a <code class="highlighter-rouge">.text-truncate</code> class to truncate the text with an ellipsis.</p>

                        <dl class="row m-b-0">
                            <dt class="col-sm-3">Description lists</dt>
                            <dd class="col-sm-9">A description list is perfect for defining terms.</dd>

                            <dt class="col-sm-3">Euismod</dt>
                            <dd class="col-sm-9">Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
                            <dd class="col-sm-9 offset-sm-3">Donec id elit non mi porta gravida at eget metus.</dd>

                            <dt class="col-sm-3">Malesuada porta</dt>
                            <dd class="col-sm-9">Etiam porta sem malesuada magna mollis euismod.</dd>

                            <dt class="col-sm-3 text-truncate">Truncated term is truncated</dt>
                            <dd class="col-sm-9">Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</dd>

                            <dt class="col-sm-3">Nesting</dt>
                            <dd class="col-sm-9">
                                <dl class="row m-b-0">
                                    <dt class="col-sm-4">Nested definition list</dt>
                                    <dd class="col-sm-8">Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc.</dd>
                                </dl>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Botones</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Examples</h4>
                        <p class="text-muted m-b-30 font-14">Bootstrap includes six predefined button styles, each serving its own semantic purpose.</p>

                        <div class="button-items">
                            <button type="button" class="btn btn-primary">Primary</button>
                            <button type="button" class="btn btn-secondary">Secondary</button>
                            <button type="button" class="btn btn-success">Success</button>
                            <button type="button" class="btn btn-info">Info</button>
                            <button type="button" class="btn btn-warning">Warning</button>
                            <button type="button" class="btn btn-danger">Danger</button>
                            <button type="button" class="btn btn-light">Light</button>
                            <button type="button" class="btn btn-dark">Dark</button>
                            <button type="button" class="btn">Botón</button>
                            <button type="button" class="btn" disabled>Disabled</button>
                            <a href="javascript:void(0);" class="btn btn-link" role="button">Link</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Outline buttons</h4>
                        <p class="text-muted m-b-30 font-14">In need of a button, but not the hefty background colors they bring? Replace the default modifier classes with the <code class="highlighter-rouge">.btn-outline-*</code> ones to remove all background images and colors on any button.</p>

                        <div class="button-items">
                            <button type="button" class="btn btn-outline btn-primary">Primary</button>
                            <button type="button" class="btn btn-outline btn-secondary">Secondary</button>
                            <button type="button" class="btn btn-outline btn-success">Success</button>
                            <button type="button" class="btn btn-outline btn-info">Info</button>
                            <button type="button" class="btn btn-outline btn-warning">Warning</button>
                            <button type="button" class="btn btn-outline btn-danger">Danger</button>
                            <button type="button" class="btn btn-outline btn-dark">Dark</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Button tags</h4>
                        <p class="text-muted m-b-30 font-14">The <code class="highlighter-rouge">.btn</code> classes are designed to be used with the <code class="highlighter-rouge">&lt;button&gt;</code> element. However, you can also use these classes on <code class="highlighter-rouge">&lt;a&gt;</code> or <code class="highlighter-rouge">&lt;input&gt;</code> elements (though some browsers may apply a slightly different rendering).</p>

                        <div class="button-items">
                            <a class="btn btn-success" href="javascript:void(0);" role="button">Link</a>
                            <button class="btn btn-primary" type="submit">Button</button>
                            <input class="btn btn-info" type="button" value="Input">
                            <input class="btn btn-warning" type="submit" value="Submit">
                            <input class="btn btn-danger" type="reset" value="Reset">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Sizes</h4>
                        <p class="text-muted m-b-30 font-14">Fancy larger or smaller buttons? Add <code class="highlighter-rouge">.btn-lg</code> or <code class="highlighter-rouge">.btn-sm</code> for additional sizes. </p>

                        <div class="button-items">
                            <button type="button" class="btn btn-info btn-lg">Large button</button>
                            <button type="button" class="btn btn-secondary btn-lg">Large button</button>
                            <button type="button" class="btn btn-info btn-sm">Small button</button>
                            <button type="button" class="btn btn-secondary btn-sm">Small button</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-body">

                        <h4 class="m-t-0 header-title">Block Buttons</h4>
                        <p class="text-muted m-b-30 font-14">Create block level buttons—those that span the full width of a parent—by adding <code class="highlighter-rouge">.btn-block</code>.</p>

                        <div class="button-items">
                            <button type="button" class="btn btn-primary btn-lg btn-block">Block level button</button>
                            <button type="button" class="btn btn-secondary btn-sm btn-block">Block level button</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dropdows</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-body">

                        <h4 class="m-t-0 header-title">Single button dropdowns</h4>
                        <p class="text-muted m-b-30 font-14">Any single <code class="highlighter-rouge">.btn</code> can be turned into a dropdown toggle with some markup changes. Here’s how you can put them to work with either <code class="highlighter-rouge">&lt;button&gt;</code> elements:</p>

                        <div class="button-items">
                            <div class="dropmenu">
                                <button class="btn">Dropdown</button>
                                <div class="dropdown">
                                    <a href="javascript:void(0);">Menu 1</a>
                                    <a>Menu 2 without href</a>
                                    <span class="space"></span>
                                    <a class="with-icon" href="javascript:void(0);"><i class="icon dripicons-alarm"></i>Menu 3</a>
                                </div>
                            </div>

                            <div class="dropmenu with-icon">
                                <button class="btn"><i class="mdi mdi-account-outline m-r-5"></i>Dropdown con icono</button>
                                <div class="dropdown">
                                    <a href="javascript:void(0);"><i class="icon dripicons-broadcast"></i>Menu 1</a>
                                    <a><i class="icon mdi mdi-account-search"></i>Menu 2 without href</a>
                                    <span class="space"></span>
                                    <a href="javascript:void(0);"><i class="icon fa fa-id-card"></i>Menu 3</a>
                                </div>
                            </div>

                            <div class="dropmenu menu-right">
                                <button class="btn">Menu right</button>
                                <div class="dropdown">
                                    <a href="javascript:void(0);">Menu 1</a>
                                    <a href="javascript:void(0);">Menu 2</a>
                                    <span class="space"></span>
                                    <a href="javascript:void(0);">Menu 3</a>
                                </div>
                            </div>

                            <div class="dropmenu dropup">
                                <button class="btn">Dropup</button>
                                <div class="dropdown">
                                    <a href="javascript:void(0);">Menu 1</a>
                                    <a href="javascript:void(0);">Menu 2</a>
                                    <span class="space"></span>
                                    <a href="javascript:void(0);">Menu 3</a>
                                </div>
                            </div>

                            <div class="dropmenu dropleft">
                                <button class="btn">Dropleft</button>
                                <div class="dropdown">
                                    <a href="javascript:void(0);">Menu 1</a>
                                    <a href="javascript:void(0);">Menu 2</a>
                                    <span class="space"></span>
                                    <a href="javascript:void(0);">Menu 3</a>
                                </div>
                            </div>

                            <div class="dropmenu dropright">
                                <button class="btn">Dropright</button>
                                <div class="dropdown">
                                    <a href="javascript:void(0);">Menu 1</a>
                                    <a href="javascript:void(0);">Menu 2</a>
                                    <span class="space"></span>
                                    <a href="javascript:void(0);">Menu 3</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Tabs y acordiones</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Toggle example</h4>
                        <p class="text-muted m-b-30 font-14">Extend the default collapse behavior to create an accordion.</p>

                        <section id="toggle" class="toggles">
                            <section class="toggle view">
                                <h3>Pregunta 1</h3>
                                <div>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate</p>
                                </div>
                            </section>
                            <section class="toggle">
                                <h3>Pregunta 2</h3>
                                <div>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate</p>
                                </div>
                            </section>
                        </section>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Accordion example</h4>
                        <p class="text-muted m-b-30 font-14">Extend the default collapse behavior to create an accordion.</p>

                        <section id="accordion" class="toggles accordion">
                            <section class="toggle">
                                <h3>Pregunta 1</h3>
                                <div>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate</p>
                                </div>
                            </section>
                            <section class="toggle view">
                                <h3>Pregunta 2</h3>
                                <div>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate</p>
                                </div>
                            </section>
                        </section>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Tabs example</h4>
                        <p class="text-muted m-b-30 font-14">Extend the default collapse behavior to create an accordion.</p>

                        <div id="multitabs" class="tabs" data-tab-active="tab1">
                            <ul>
                                <li data-tab-target="tab1">Tab 1</li>
                                <li data-tab-target="tab2" disabled>Tab 2 (desactivado)</li>
                                <li data-tab-target="tab3">Tab 3</li>
                            </ul>

                            <div class="tab" data-target="tab1">
                                <span class="p-l-10 p-r-10">tab 1</span>

                                <button class="btn btn-primary btn-block m-t-10" id="gotabtwo">Ir al tab 2</button>
                            </div>
                            <div class="tab" data-target="tab2">
                                tab 2
                                <br>
                                El tab 2, solo es accesible por un boton, o accion.
                            </div>
                            <div class="tab" data-target="tab3">
                                tab 3
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Modals</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Modals Examples</h4>
                        <p class="text-muted m-b-30 font-14">Modals are streamlined, but flexible dialog prompts powered by JavaScript. They support a number of use cases from user notification to completely custom content and feature a handful of helpful subcomponents, sizes, and more.</p>

                        <div class="button-items">
                            <button type="button" class="btn bnt-primary" data-button-modal="example-1">Standar modal</button>
                            <button type="button" class="btn bnt-primary" data-button-modal="example-2">Center modal</button>
                            <button type="button" class="btn bnt-primary" data-button-modal="example-3">Small modal</button>
                            <button type="button" class="btn bnt-primary" data-button-modal="example-4">Large modal</button>
                            <button type="button" class="btn bnt-primary" data-button-modal="example-5">Fullscreen</button>
                        </div>

                        <section class="modal" data-modal="example-1">
                            <div class="content">
                                <header>
                                    <h3>Standar modal</h3>
                                </header>
                                <main>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>

                                    <p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>
                                </main>
                                <footer>
                                    <div class="action-buttons">
                                        <button class="btn btn-link" button-close>Cerrar</button>
                                        <button class="btn btn-primary" button-submit>Aceptar</button>
                                    </div>
                                </footer>
                            </div>
                        </section>

                        <section class="modal centered" data-modal="example-2">
                            <div class="content">
                                <header>
                                    <h3>Modal centrado</h3>
                                </header>
                                <main>
                                    <p class="m-0">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                                </main>
                                <footer>
                                    <div class="action-buttons">
                                        <button class="btn btn-link" button-close>Cerrar</button>
                                        <button class="btn btn-primary" button-submit>Aceptar</button>
                                    </div>
                                </footer>
                            </div>
                        </section>

                        <section class="modal size-small" data-modal="example-3">
                            <div class="content">
                                <header>
                                    <h3>Tamaño chico</h3>
                                </header>
                                <main>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                                </main>
                                <footer>
                                    <div class="action-buttons">
                                        <button class="btn btn-link" button-close>Cerrar</button>
                                        <button class="btn btn-primary" button-submit>Aceptar</button>
                                    </div>
                                </footer>
                            </div>
                        </section>

                        <section class="modal size-big" data-modal="example-4">
                            <div class="content">
                                <header>
                                    <h3>Tamaño grande</h3>
                                </header>
                                <main>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>

                                    <p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>
                                </main>
                                <footer>
                                    <div class="action-buttons">
                                        <button class="btn btn-link" button-close>Cerrar</button>
                                        <button class="btn btn-primary" button-submit>Aceptar</button>
                                    </div>
                                </footer>
                            </div>
                        </section>

                        <section id="example-5" class="modal fullscreen" data-modal="example-5">
                            <div class="content">
                                <header>
                                    <h3>Pantalla completa</h3>
                                </header>
                                <main>
                                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>

                                    <p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>
                                </main>
                                <footer>
                                    <div class="action-buttons">
                                        <button class="btn btn-link" button-close>Cerrar</button>
                                        <button class="btn btn-primary" button-submit>Aceptar</button>
                                    </div>
                                </footer>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Inputs</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-sm-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Validation type</h4>
                        <p class="text-muted m-b-30 font-14">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p>

                        <div class="label">
                            <label>
                                <p>Usuario</p>
                                <input type="text"/>
                                <p class="description">Descripción del "input".</p>
                            </label>
                        </div>

                        <div class="label">
                            <label class="error">
                                <p>Password</p>
                                <input type="password" />
                                <p class="description">Descripción del "input".</p>
                                <p class="error">Error en este campo</p>
                            </label>
                        </div>

                        <div class="label">
                            <label>
                                <p>Select</p>
                                <select>
                                    <option selected disabled hidden>Elegir...</option>
                                    <option value="1">Opcion 1</option>
                                    <option value="1">Opcion 2</option>
                                    <option value="1">Opcion 3</option>
                                </select>
                                <p class="description">Descripción del "select".</p>
                            </label>
                        </div>

                        <div class="label">
                            <label>
                                <p>Textarea</p>
                                <textarea rows="8" cols="80"></textarea>
                                <p class="description">Descripción del "textarea".</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="m-t-0 header-title">Validation type</h4>
                        <p class="text-muted m-b-30 font-14">Parsley is a javascript form validation library. It helps you provide your users with feedback on their form submission before sending it to your server.</p>

                        <div class="label group-labels">
                            <label class="checkbox">
                                <p>Default</p>
                                <input type="checkbox" checked/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Primary</p>
                                <input type="checkbox" class="input-primary" checked/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Success</p>
                                <input type="checkbox" class="input-success" checked/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Info</p>
                                <input type="checkbox" class="input-info" checked/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Warning</p>
                                <input type="checkbox" class="input-warning" checked/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Danger</p>
                                <input type="checkbox" class="input-danger" checked/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Dark</p>
                                <input type="checkbox" class="input-dark" checked/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Disabled</p>
                                <input type="checkbox" checked disabled/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                            <label class="checkbox">
                                <p>Disabled Un-checked</p>
                                <input type="checkbox" disabled/>
                                <div class="checkbox_indicator"></div>
                                <p class="description">Descripción del "checkbox".</p>
                            </label>
                        </div>

                        <div class="label group-labels m-t-30">
                            <label class="radio">
                                <p>Default</p>
                                <input type="radio" name="radio" checked/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Primary</p>
                                <input type="radio" name="primary" class="input-primary" checked/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Success</p>
                                <input type="radio" name="success" class="input-success" checked/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Info</p>
                                <input type="radio" name="info" class="input-info" checked/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Warning</p>
                                <input type="radio" name="warning" class="input-warning" checked/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Danger</p>
                                <input type="radio" name="danger" class="input-danger" checked/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Dark</p>
                                <input type="radio" name="dark" class="input-dark" checked/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Disabled</p>
                                <input type="radio" name="disabled" checked disabled/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                            <label class="radio">
                                <p>Un-checked</p>
                                <input type="radio" name="radio"/>
                                <div class="radio_indicator"></div>
                                <p class="description">Descripción del "radio".</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Cards</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <!-- Simple card -->
                <div class="card m-b-30">
                    <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title font-20 m-t-0">Card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make
                            up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary waves-effect waves-light">Button</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card m-b-30">
                    <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title font-20 m-t-0">Card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make
                            up the bulk of the card's content.</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Cras justo odio</li>
                        <li class="list-group-item">Dapibus ac facilisis in</li>
                    </ul>
                    <div class="card-body">
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card m-b-30">
                    <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="card-title font-20 m-t-0">Card title</h4>
                        <h6 class="card-subtitle text-muted">Support card subtitle</h6>
                    </div>
                    <img class="img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-body m-b-30">
                    <h3 class="card-title font-20 m-t-0">Special title treatment</h3>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary waves-effect waves-light">Go somewhere</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body m-b-30">
                    <h3 class="card-title font-20 m-t-0">Special title treatment</h3>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary waves-effect waves-light">Go somewhere</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card card-body m-b-30">
                    <h4 class="card-title font-20 m-t-0">Special title treatment</h4>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary waves-effect waves-light">Go somewhere</a>
                </div>
            </div>

            <div class="col-xl-4 ">
                <div class="card card-body m-b-30 text-center">
                    <h4 class="card-title font-20 m-t-0">Special title treatment</h4>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary waves-effect waves-light">Go somewhere</a>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card card-body m-b-30 text-right">
                    <h4 class="card-title font-20 m-t-0">Special title treatment</h4>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary waves-effect waves-light">Go somewhere</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card m-b-30">
                    <h4 class="card-header m-t-0">Featured</h4>
                    <div class="card-body">
                        <h4 class="card-title font-20 m-t-0">Special title treatment</h4>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30">
                    <div class="card-header">
                        Quote
                    </div>
                    <div class="card-body">
                        <blockquote class="card-bodyquote">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30">
                    <div class="card-header">
                        Featured
                    </div>
                    <div class="card-body">
                        <h4 class="card-title font-20 m-t-0">Special title treatment</h4>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary waves-effect waves-light">Go somewhere</a>
                    </div>
                    <div class="card-footer text-muted">
                        2 days ago
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card m-b-30">
                    <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title font-20 m-t-0">Card title</h4>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="card-title font-20 m-t-0">Card title</h4>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </p>
                    </div>
                    <img class="card-img-bottom img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30">
                    <img class="card-img img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image">
                    <div class="card-img-overlay">
                        <h4 class="card-title text-white font-20 m-t-0">Card title</h4>
                        <p class="card-text text-light">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text">
                            <small class="text-white">Last updated 3 mins ago</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card m-b-30 text-white" style="background-color: #333; border-color: #333;">
                    <div class="card-body">
                        <h3 class="card-title font-20 m-t-0">Special title treatment</h3>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Button</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30 text-white bg-primary">
                    <div class="card-body">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer text-white font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30 text-white bg-success">
                    <div class="card-body">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer text-white font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card m-b-30 text-white bg-info">
                    <div class="card-body">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer text-white font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30 text-white bg-warning">
                    <div class="card-body">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer text-white font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card m-b-30 text-white bg-danger">
                    <div class="card-body">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer text-white font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card-group">
                    <div class="card m-b-30">
                        <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-20 m-t-0">Card title</h4>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </p>
                        </div>
                    </div>
                    <div class="card m-b-30">
                        <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-20 m-t-0">Card title</h4>
                            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                            <p class="card-text">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </p>
                        </div>
                    </div>
                    <div class="card m-b-30">
                        <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-20 m-t-0">Card title</h4>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                            <p class="card-text">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4 class="m-t-20 m-b-30">Decks</h4>
                <div class="card-deck-wrapper">
                    <div class="card-deck">
                        <div class="card m-b-30">
                            <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title font-20 m-t-0">Card title</h4>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text">
                                    <small class="text-muted">Last updated 3 mins ago</small>
                                </p>
                            </div>
                        </div>
                        <div class="card m-b-30">
                            <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title font-20 m-t-0">Card title</h4>
                                <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                                <p class="card-text">
                                    <small class="text-muted">Last updated 3 mins ago</small>
                                </p>
                            </div>
                        </div>
                        <div class="card m-b-30">
                            <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title font-20 m-t-0">Card title</h4>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                                <p class="card-text">
                                    <small class="text-muted">Last updated 3 mins ago</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4 class="m-t-20 m-b-30">Columns</h4>
                <div class="card-columns">
                    <div class="card m-b-30">
                        <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-20 m-t-0">Card title that wraps to a new line</h4>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        </div>
                    </div>
                    <div class="card card-body m-b-30">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                    <div class="card m-b-30">
                        <img class="card-img-top img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-20 m-t-0">Card title</h4>
                            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                            <p class="card-text">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </p>
                        </div>
                    </div>
                    <div class="card card-body m-b-30 card-primary">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat.</p>
                            <footer class="blockquote-footer font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                    <div class="card card-body m-b-30">
                        <h4 class="card-title font-20 m-t-0">Card title</h4>
                        <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                        <p class="card-text">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </p>
                    </div>
                    <div class="card m-b-30">
                        <img class="card-img img-fluid" src="https://via.placeholder.com/800x533/34404b/5c6872" alt="Card image cap">
                    </div>
                    <div class="card card-body m-b-30 text-right">
                        <blockquote class="card-bodyquote m-b-0">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer font-14">
                                Someone famous in <cite title="Source Title">Source Title</cite>
                            </footer>
                        </blockquote>
                    </div>
                    <div class="card card-body m-b-30">
                        <h4 class="card-title font-20 m-t-0">Card title</h4>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                        <p class="card-text">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
