<?php


require plugin_dir_path(__FILE__) . 'class-ap-custom-metabox.php';
if (!class_exists('Mi_Team_Metabox_apply')):
    class Mi_Team_Metabox_apply extends Ap_custom_Metabox
    {

        private $metafield;

        public function __construct()
        {

            $this->metafield = new Ap_custom_Metabox();
            $this->metafield->set_meta_fields($this->mi_get_settings_field());
            add_action('ap_display_function_url_example', array($this, 'displayfunction'));
        }


        public function mi_get_settings_field()
        {

            $fields = array(


                array(
                    'label'         => 'Example Metabox',
                    'id'            => 'ap_example_meta',
                    'post_options'  => array(
                        'post_type' => 'post',
                        'context'   => 'normal',
                        'priority'  => 'default'
                        ),
                    'inputs'        => array(

                        array(
                            'label'         => 'Text Example',
                            'name'          => 'text_example',
                            'type'          => 'text',
                            'class'         => 'texmple',
                            'default'       => null,
                            'placeholder'   => 'email',
                            'description'   => 'write: email to see hidden Email field',
                        ),

                        array(
                            'label' => 'Email Example',
                            'name' => 'email_example',
                            'type' => 'email',
                            'class' => 'emexmple',
                            'default' => 'abcd@def.com' ,
                            'description' => 'write: abcd@def.com to see hidden URL field',
                            'dependency' => array(
                                    'name'=> 'texmple',
                                    'value' => 'email',
                                )

                        ),

                        array(
                            'label' => 'URL Example',
                            'name' => 'url_example',
                            'type' => 'url',
                            'class' => 'urlexmple',
                            'default' => null,
                            'placeholder'=> 'google.com',
                            'dependency' => array(
                                'name'=> 'emexmple',
                                'value' => 'abcd@def.com',
                                'sub' => 'texmple'
                            )
                        ),

                        array(
                            'label' => 'Color Example',
                            'name' => 'clr_example',
                            'type' => 'color',
                            'class' => 'clrexmple',
                            'default' => 'red',

                        ),

                        array(
                            'label' => 'Select Example',
                            'name' => 'select_example',
                            'type' => 'select',
                            'input_option' =>array(
                                'one' => 1,
                                'two' => 2,
                                'three' => 3,
                            ),
                            'class' => 'slexmple',
                            'default' => 'one',
                            'placeholder'=> 'google.com',

                        ),

                        array(
                            'label' => 'Radio Example',
                            'name' => 'radio_example',
                            'type' => 'radio',
                            'input_option' =>array(
                                'one' => 1,
                                'two' => 2,
                                'three' => 3,
                            ),
                            'class' => 'rdexmple',
                            'default' => 'one',
                            'placeholder'=> 'google.com',

                        ),

                        array(
                            'name' => 'mi_experience_info',
                            'label' => 'Repeater Field Example',
                            'type' => 'repeater',
                            'child_inputs' => array(
                                array(
                                    'name' => 'mi_job_title',
                                    'type' => 'text',
                                    'label' => 'Job Title',
                                    'value' => 'professor'
                                ),

                                array(
                                    'name' => 'mi_job_form',
                                    'type' => 'text',
                                    'label' => 'Form',
                                    'description' => 'Ex: 25th August , 2015'


                                ),
                                array(
                                    'name' => 'mi_job_to',
                                    'type' => 'text',
                                    'label' => 'To',
                                    'description' => 'Ex: 25th June , 2017'

                                ),
                                array(
                                    'name' => 'mi_job_responbility',
                                    'type' => 'textarea',
                                    'label' => 'Responsibilities',

                                )


                            )
                        ),


                    )


                ),

                array(
                    'label'         => 'Gallery Example',
                    'id'            => 'gl_example_meta',
                    'post_options'  => array(
                        'post_type' => 'post',
                        'context'   => 'side',
                        'priority'  => 'default'
                    ),
                    'inputs'        => array(
                        array(
                            'name'          => 'gallery_example',
                            'type'          => 'gallery',
                            'class'         => 'glexmple',
                        ),
                    )

                )


            );
            return apply_filters('mi_meta_apply', $fields);

        }

        function displayfunction($v)
        {

            $embed_code = wp_oembed_get($v, array('width' => 350, 'height' => 150)); ?>

            <?php echo $embed_code;
        }


    }
endif;

/*================================Filter Example========================*/

//add_filter('mi_meta_apply',function($fields){
//
//    $fields = array();
//
//    $fields[] = array(
//
//        'label' => 'Personal Info',
//        'name' => 'mi_tm_personal_section',
//        'type' => 'group',
//        'post_options' => array(
//            'post_type' => "demo",
//        ),
//        'inputs' => array(
//            array(
//                'label' => 'Designation',
//                'name' => 'designation',
//                'class' => 'vvv',
//                'type' => 'text',
//                'desc' => null,
//                'default' => null,
//                'description' => 'Ex: WordPress Developer',
//            ),
//
//
//            array(
//                'label' => 'Contact Form',
//                'name' => 'cf_shortcode',
//                'type' => 'text',
//                'class' => 'ddd',
//                'description' => 'Ex: [contact-form-7 id="121" title="MR X  Contact"]',
//                'dependency' => array(
//                    'name'=> 'vvv',
//                    'value' => 1,
//                )
//            ),
//
//            array(
//                'label' => 'demo',
//                'name' => 'demo',
//                'type' => 'text',
//                'class' => 'eee',
//                'description' => 'Ex: [contact-form-7 id="121" title="MR X  Contact"]',
//                'dependency' => array(
//                    'name'=> 'ddd',
//                    'value' => 2,
//                    'sub'   => 'vvv'
//                )
//
//            ),
//
//            array(
//                'label' => 'demo1',
//                'name' => 'demo1',
//                'type' => 'text',
//                'class' => 'fff',
//                'description' => 'Ex: [contact-form-7 id="121" title="MR X  Contact"]',
//                'dependency' => array(
//                    'name'=> 'eee',
//                    'value' => 2,
//                    'sub'   => 'ddd'
//                )
//
//            ),
//
//            array(
//                'name' => 'mi_skill_info',
//                'label' => 'Skill Infos',
//                'type' => 'repeater',
//                'child_inputs' => array(
//                    array(
//                        'name' => 'mi_skill_title',
//                        'type' => 'text',
//                        'label' => 'Skill Name',
//                        'default'=> 'php'
//                    ),
//
//                    array(
//                        'name' => 'mi_skill_percent',
//                        'type' => 'text',
//                        'label' => 'Percentage',
//                        'description' => 'Ex:85'
//
//                    ),
//
//                )
//            ),
//
//
//
//
//        )
//
//
//    );
//
//    return $fields;
//});




new Mi_Team_Metabox_apply();