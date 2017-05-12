# Custom-Metabox PHP script for WordPress Plugin 
-----------------------------------------------
Custom metabox  for wp.org  plugins . This Script written for developer only. It is not a plugin or theme . It is a script to include with your plugin & create metabox easily.

## Features 
-----------
* Easy to Customize 
* Tested with wp.org & codecanyon Plugins
* Repeater Fields
* Nested Dependancy 
* Developer Friendly 


##Usage Example
---------------
Checkout the class-ap-custom-metabox-apply.php file. 

```php
$fields[] = array(
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


    );
```



## Screenshot

![ap custom Metabox](https://github.com/apurbajnu/Custom-Metabox/blob/master/Screenshot.png "Custom Metabox Screenshot")


##Frequently Asked Questions
----------------------------
#### What is this ? 

It is a PHP script for WordPress plugin to generate metabox easily 

####  Is it a WordPress Plugin or Theme ? 

It is not a plugin nor a theme. Just a script. Specially for developing plugin for wordpress.org. 

#### Who will use it ? 

This script for developers only not for end users. 

#### Where is documentation? 

Documentation will be available within short time. It is developer friendly script , hope you will understand easily. If you have any question just ask me. apurba.jnu@gmail.com 

###License
----------
MIT license. (http://opensource.org/licenses/MIT)