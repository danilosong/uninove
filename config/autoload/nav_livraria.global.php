<?php


//echo '<pre>', var_dump(getcwd()), '<br>', __NAMESPACE__ ; die;
/*
 * Esse array foi substituido pelo array do bd
 */

return array(
    // All navigation-related configuration is collected in the 'navigation' key
    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'home' => array(
                'label' => 'Home',
                'route' => 'app/default',
            ),
            'Login' => array(
                'label' => 'Login',
                'route' => 'application-auth',
            ),
        ),
        'admin' => array(
            // And finally, here is where we define our page hierarchy
            '1' => array(
                'label' => '<i class="fa fa-search fa-fw"></i>welcome',
                'route' => 'app/default',
                'attribs'   => array(   // atributos para <a>
                ),
                'controller' => 'index',
                'action' => 'bemVindo',
            ),
            '1' => array(
                'label' => '<i class="fa fa-search fa-fw"></i>menu config',
                'route' => 'app/default',
                'attribs'   => array(   // atributos para <a>
                ),
                'controller' => 'appMenus',
                'action' => 'index',
            ),
            '2' => array(
                'label' => '<i class="fa fa-search fa-fw"></i>Home<span class="fa arrow"></span>',
                'wrapClass' => 'primary',         // class to <li>
                'class'     => 'dropdown-toggle',  // class to <a> like usual
                'controller' => '',
                'pages' => array(
                    'seguradoras' => array(
                        'label' => '<i class="fa fa-search fa-fw"></i>Seguradora<span class="fa arrow"></span>',
                        'route' => 'app/default',
                        'pagesContainerClass' => 'nav nav-second-level',
                        'controller' => 'seguradoras',
                        'pages' => array(
                            'seguradora1' => array(
                                'label' => '<i class="fa fa-dashboard fa-fw"></i>Seguradora',
                                'route' => 'app/default',
                                'controller' => 'seguradoras',
                                'action' => 'index',
                                'pagesContainerClass' => 'nav nav-third-level',
                            ),
                            'taxas1' => array(
                                'label' => '<i class="fa fa-search fa-fw"></i>Taxa Coberturas',
                                'route' => 'app/default',
                                'controller' => 'taxas',
                                'action' => 'index',
                            ),
                            'Mult_Min1' => array(
                                'label' => '<i class="fa fa-pencil fa-fw"></i>Limites de Contratação',
                                'route' => 'app/default',
                                'controller' => 'multiplosMinimos',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'welcome2' => array(
                'label' => '<i class="fa fa-search fa-fw"></i>welcome',
                'route' => 'app/default',
                'attribs'   => array(   // atributos para <a>
                ),
                'controller' => 'index',
                'action' => 'bemVindo',
            ),
            'logout' => array(
                'label' => 'Logout',
                'route' => 'application-logout',
            ),
            'home2' => array(
                'label' => '<i class="fa fa-search fa-fw"></i>Home<span class="fa arrow"></span>',
                'route' => 'app/default',
                'wrapClass' => 'primary',         // class to <li>
//                'class'     => 'dropdown-toggle',  // class to <a> like usual
                'controller' => 'index',
                'action' => 'bemVindo',
                'pages' => array(
                    'seguradoras' => array(
                        'label' => '<i class="fa fa-search fa-fw"></i>Seguradora',
                        'route' => 'app/default',
                        'controller' => 'seguradoras',
                    ),
                ),
            ),
            'cadastros' => array(
                'label' => 'Cadastro',
                'route' => 'app/default',
                'controller' => 'index',
                'action' => 'cadastro',
                'pages' => array(
                    'seguradoras' => array(
                        'label' => 'Seguradora',
                        'route' => 'app/default',
                        'pagesContainerClass' => 'dropdown-menu',
                        'controller' => 'seguradoras',
                        'pages' => array(
                            'seguradora1' => array(
                                'label' => 'Seguradoras',
                                'route' => 'app/default',
                                'controller' => 'seguradoras',
                                'action' => 'index',
                            ),
                            'taxas1' => array(
                                'label' => 'Taxa_Coberturas',
                                'route' => 'app/default',
                                'controller' => 'taxas',
                                'action' => 'index',
                            ),
                            'Mult_Min1' => array(
                                'label' => 'Limites_de_Contratação',
                                'route' => 'app/default',
                                'controller' => 'multiplosMinimos',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'administradora' => array(
                        'label' => 'Administradora',
                        'route' => 'app/default',
                        'controller' => 'administradoras',
                        'pages' => array(
                            'administradora1' => array(
                                'label' => 'Administradoras',
                                'route' => 'app/default',
                                'controller' => 'administradoras',
                                'action' => 'index',
                            ),
                            'administradora2' => array(
                                'label' => 'Comissão_Paramentros',
                                'route' => 'app/default',
                                'controller' => 'comissaos',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'classe' => array(
                        'label' => 'Classe',
                        'route' => 'app/default',
                        'controller' => 'classes',
                        'pages' => array(
                            'classe1' => array(
                                'label' => 'Classes',
                                'route' => 'app/default',
                                'controller' => 'classes',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'atividades' => array(
                        'label' => 'Atividade',
                        'route' => 'app/default',
                        'controller' => 'atividades',
                        'pages' => array(
                            'atividades1' => array(
                                'label' => 'Atividades',
                                'route' => 'app/default',
                                'controller' => 'atividades',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'classeAtividade' => array(
                        'label' => 'Classes Atividade',
                        'route' => 'app/default',
                        'controller' => 'classeAtividades',
                        'pages' => array(
                            'classeAtividade1' => array(
                                'label' => 'Classes_Atividades',
                                'route' => 'app/default',
                                'controller' => 'classeAtividades',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'users' => array(
                        'label' => 'Usuario',
                        'route' => 'app/default',
                        'controller' => 'users',
                        'pages' => array(
                            'users1' => array(
                                'label' => 'Usuarios',
                                'route' => 'app/default',
                                'controller' => 'users',
                                'action' => 'index',
                            ),
                            'users2' => array(
                                'label' => 'Altera Senha',
                                'route' => 'app/default',
                                'controller' => 'users',
                                'action' => 'alteraSenha',
                            ),
                        ),
                    ),
                    'locatarios' => array(
                        'label' => 'Locatario',
                        'route' => 'app/default',
                        'controller' => 'locatarios',
                        'pages' => array(
                            'locatarios1' => array(
                                'label' => 'Locatarios',
                                'route' => 'app/default',
                                'controller' => 'locatarios',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'locadors' => array(
                        'label' => 'Locador',
                        'route' => 'app/default',
                        'controller' => 'locadors',
                        'pages' => array(
                            'locadors1' => array(
                                'label' => 'Locadores',
                                'route' => 'app/default',
                                'controller' => 'locadors',
                                'action' => 'index',
                            ),
                            'locadors2' => array(
                                'label' => 'Imoveis',
                                'route' => 'app/default',
                                'controller' => 'imovels',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'Parametro' => array(
                        'label' => 'Parametro',
                        'route' => 'app/default',
                        'controller' => 'parametroSis',
                        'pages' => array(
                            'Parametro1' => array(
                                'label' => 'Parametros',
                                'route' => 'app/default',
                                'controller' => 'parametroSis',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),                
            ),
        ),
    ),
);
