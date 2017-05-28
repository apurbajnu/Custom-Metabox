<?php

/**
 * include setiings class only for extend;
 */



if (!class_exists('Ap_custom_Metabox')):
    class Ap_custom_Metabox
    {


        /**
         * @var meta_fields
         */

        private $set_meta_fields;


        /**
         * dependacy array store
         * @var array
         */

        private $dependancy_store = array();

        private $defaults = array(

            'label' => null,
            'name' => '',
            'type' => 'text',
            'class' => null,
            'default' => null,
            'placeholder' => '',
            'description' => '',
            'input_option'=>''

        );


        public function __construct()
        {
            add_action("add_meta_boxes", array($this, "add_custom_meta_box"));
            add_action("save_post", array($this, "save_custom_meta_box"), 10, 3);
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        }


        public function __call($method, $args)
        {
            if (isset($this->$method)) {
                $func = $this->$method;
                return call_user_func_array($func, $args);
            }
        }

        /**
         * Enqueue scripts and styles
         */
        function admin_enqueue_scripts()
        {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('meta_css',plugin_dir_url( __FILE__ ).'/css/ap_custom_meta.css');
            wp_enqueue_media();
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery');
            wp_enqueue_script('dep-js', plugin_dir_url( __FILE__ ).'/js/dep.js', array('jquery'), 1, false);
            wp_enqueue_script('meta-js', plugin_dir_url( __FILE__ ).'/js/meta.js', array('jquery','dep-js'), 1, false);
            wp_enqueue_script('repeater-js', plugin_dir_url( __FILE__ ).'/js/jquery.repeater.min.js', array('jquery'), 1, false);
            wp_localize_script('meta-js', 'dependancy_meta', $this->dependancy_store);
        }


        /**
         * @param $dependancy
         * @return $this
         */
        function set_dependancy($dependancy)
        {

            $this->dependancy_store [] = $dependancy;

            return $this;

        }


        /**
         * @param $fields
         */
        public function set_meta_fields($fields)
        {

            $fields = array_map(function ($e) {
                return array_merge($this->defaults, $e);
            }, $fields);

            $this->set_meta_fields = $fields;


        }


        /**
         * custom metabox add function
         */

        function add_custom_meta_box()
        {

            foreach ($this->set_meta_fields as $field) {
                $id = $field['id'];
                $title = $field['label'];
                $description = $field['description'];
                $label = $field['label'];
                $callback = "callback_ap_group";
                $ap_dependancy_prop = array();
                $childinput = $field['inputs'];

                    foreach ($childinput as $child) {

                        if (array_key_exists('dependency', $child)) {
                            $ap_dependancy_prop['dependency']['name'] = "." .$child['dependency']['name'];
                            $ap_dependancy_prop['dependency']['value'] = $child['dependency']['value'];
                            $ap_dependancy_prop['dependency']['target'] = "." . $child['class'];
                            $ap_dependancy_prop['dependency']['sub'] = (array_key_exists('sub', $child['dependency']))? $child['dependency']['sub']:null;
                            $this->set_dependancy($ap_dependancy_prop['dependency']);
                        }

                    }

                $screen = isset($field['post_options']['post_type']) ? $field['post_options']['post_type'] : "post";
                $context = isset($field['post_options']['context']) ? $field['post_options']['context'] : 'normal';
                $priority = isset($field['post_options']['priority']) ? $field['post_options']['priority'] : 'default';
                $default = $field['default'];
                $callback_args = array(
                    'id' => $id,
                    'label' => $label,
                    'name' => $id,
                    'child_inputs' => $childinput,
                    'description' => $description,
                );
                add_meta_box($id, $title, array($this, $callback), $screen, $context, $priority, $callback_args);

            }


        }



        /**
         * @param $arg
         * @param $value
         * @return string
         */

        function ap_input_text($arg, $value, $object)
        {

            if (!array_key_exists('id', $arg)) {
                $arg['id'] = $arg['name'];
            }
            return sprintf("<div class='mb-box-class %s'><label for='%s'>%s</label><input type='text' id='%s' name='%s' value='%s' placeholder='%s' ><p class='description'>%s</p></div>", esc_attr($arg['class']), esc_attr($arg['id']), esc_attr($arg['label']), esc_attr($arg['id']), esc_attr($arg['name']), esc_html($value), esc_attr($arg['placeholder']), esc_attr($arg['description']));

        }

        /**
         * @param $arg
         * @param $value
         * @param $object
         * @return string
         */

        function ap_input_email($arg, $value, $object)
        {
            if (!array_key_exists('id', $arg)) {
                $arg['id'] = $arg['name'];
            }
            return sprintf("<div class='mb-box-class %s'><label for='%s'>%s</label><input type='email' id='%s' name='%s' value='%s' placeholder='' ><p class='description'>%s</p></div>", esc_attr($arg['class']), esc_attr($arg['id']), esc_attr($arg['label']), esc_attr($arg['id']), esc_attr($arg['name']), esc_html($value), esc_attr($arg['placeholder']), esc_attr($arg['description']));

        }

        /**
         * @param $arg
         * @param $value
         * @return url
         */

        function ap_input_url($arg, $value, $object)
        {

            if (!array_key_exists('id', $arg)) {
                $arg['id'] = $arg['name'];
            }

            return sprintf("<div class='mb-box-class %s'> <label for='%s'>%s</label><input type='text' id='%s'  name='%s' value='%s' placeholder='%s' ><p class='description'>%s</p></div>", esc_attr($arg['class']), esc_attr($arg['id']), esc_attr($arg['label']), esc_attr($arg['id']), esc_attr($arg['name']), esc_url($value), esc_attr($arg['placeholder']), esc_attr($arg['description']));

        }

        /**
         * @param $arg
         * @param $value
         * @param $object
         * @return string
         */
        function ap_input_hidden($arg, $value, $object)
        {

            return sprintf("<input type='hidden' id='%s' name='%s' value='%s' >", esc_attr($arg['class']), esc_attr(
                $arg['name']), $value);

        }

        /**
         * @param $arg
         * @param $value
         * @return json
         */

        function ap_input_gallery($arg, $value, $object)
        {

            if (!array_key_exists('id', $arg)) {
                $arg['id'] = $arg['name'];
            }
            // Get WordPress' media upload URL
            $upload_link = esc_url(get_upload_iframe_src('image', $object->ID));

            // See if there's a media id already saved as post meta
            $selected_images_id = $value;


            // Get the image src
            $selected_images_src = wp_get_attachment_image_src($selected_images_id, 'full');

            // For convenience, see if the array is valid
            $has_image = is_array($selected_images_src);
            ?>
            <div class="gallery-image-metabox">
                <div id="<?php echo $arg['id'] ?>">
                    <!-- Your image container, which can be manipulated with js -->
                    <div class="custom-img-container">
                        <?php if ($has_image) : ?>
                            <img src="<?php echo $selected_images_src[0] ?>" alt="" style="max-width:100%;"/>
                        <?php endif; ?>
                    </div>

                    <!-- Your add & remove image links -->
                    <p class="hide-if-no-js">
                        <a class="upload-custom-img <?php if ($has_image) {
                            echo 'hidden';
                        } ?> <?php echo $arg['class'] ?>" id="<?php echo $arg['name']; ?>"
                           href="<?php echo $upload_link ?>">
                            <?php _e('Set custom image') ?>
                        </a>
                        <a class="delete-custom-img <?php if (!$has_image) {
                            echo 'hidden';
                        } ?>"
                           href="#">
                            <?php _e('Remove this image') ?>
                        </a>
                    </p>
                    <!-- A hidden input to set and post the chosen image id -->
                    <label for="<?php echo esc_attr($arg['id']); ?>"><?php esc_html_e($arg['label']) ?></label>
                    <input class="custom-img-id" id="<?php echo esc_attr($arg['id']); ?>"
                           name="<?php echo esc_attr($arg['name']); ?>" type="hidden"
                           value="<?php echo esc_attr($selected_images_id); ?>"/>
                </div>
            </div><!--end of gallery image meta box-->
            <?php

        }

        /**
         * @param $arg
         * @param $value
         * @param $object
         * @return color hexcode
         */


        function ap_input_color($arg, $value, $object)
        {
            if (!array_key_exists('id', $arg)) {
                $arg['id'] = $arg['name'];
            }
            return sprintf("<div class='mb-box-class %s'><label for='%s'>%s</label> <div class='color-metabox'><input type='hidden' id='%s' class='widefat' name='%s' value='%s' ><p class='description'>%s</p></div></div>", esc_attr($arg['class']), esc_attr($arg['id']), esc_attr($arg['label']), esc_attr($arg['id']), esc_attr($arg['name']), esc_url($value), esc_attr($arg['description']));

        }


        function ap_input_textarea($arg, $value, $object)
        {
            if (!array_key_exists('id', $arg)) {
                $arg['id'] = $arg['name'];
            }
            return sprintf("<div class='mb-box-class %s'><label for='%s'>%s</label><textarea id='%s' style='margin-top: 12px; margin-bottom: 0px; height: 60px;' rows='1' cols='40' type='text' class='widefat' name='%s' placeholder='%s'>%s</textarea><p class='description'>%s</p></div>", esc_attr($arg['class']), esc_attr($arg['id']), esc_attr($arg['label']), esc_attr($arg['id']), esc_attr(
                $arg['name']), esc_attr($arg['placeholder']), esc_html($value), esc_attr($arg['description']));
        }

        /**
         * @param $arg
         * @param $value
         * @param $object
         */

        function ap_input_radio($arg, $value, $object)
        {

            $options = $options = $arg['input_option'];;
            $checked = '';
            $i = 0;
            ?>
            <div class='mb-box-class <?php echo $arg['class'] ?>'>
                <label><?php echo esc_html($arg['label']); ?></label>
                <div class="radio-container">
                <?php
                foreach ($options as $key => $option):
                    $i++;

                    if ($i == 1 && $value == null) {
                        $checked = "checked";
                    } else if ($key == $value) {
                        $checked = "checked";
                    } else {
                        $checked = '';
                    }
                    ?>


                    <input type="radio" id="<?php esc_html_e(ucfirst($option)) ?>"
                           name="<?php echo esc_html($arg['name']); ?>"
                           value="<?php esc_html_e($key) ?>" <?php echo esc_html($checked); ?>>
                    <label class="radio-label" for="<?php esc_html_e(ucfirst($option)) ?>"><?php esc_html_e(ucfirst($option)) ?></label>
                    <br>
                <?php endforeach; ?>
                </div>
                <p class="description"><?php echo esc_attr($arg['description']) ?></p>
            </div>
            <?php

        }
        /**
         * @param $arg
         * @param $value
         * @param $object
         */

        function ap_input_select($arg, $value, $object)
        {

            $options = $arg['input_option'];
            if (!array_key_exists('id', $arg)) {
                $arg['id'] = $arg['name'];
            }
            ?>
            <div class='mb-box-class <?php echo $arg['class'] ?>'>
                <label for="<?php echo esc_html($arg['id']); ?>"><?php echo esc_html($arg['label']); ?></label>

                <select id="<?php echo esc_html($arg['id']); ?>"
                        name="<?php echo esc_html($arg['name']); ?>">
                    <?php
                    foreach ($options as $key => $option):
                        $ap_selected = '';
                        if ($key == $value) {
                            $ap_selected = 'selected';
                        }
                        ?>

                        <option <?php echo esc_attr($ap_selected); ?>
                            value="<?php esc_html_e($key) ?>"><?php esc_html_e(ucfirst($option)) ?></option>

                    <?php endforeach; ?>
                </select>
                <p class="description"><?php echo esc_attr($arg['description']) ?></p>
            </div>
            <?php

        }

        /**
         * @param $object
         * @param $ap_value
         */
        function callback_ap_group($object, $ap_value)
        {
            $arguments = $ap_value['args'];
            $inputs = $arguments['child_inputs'];
            $value = get_post_meta($object->ID, $arguments['name'], true);
            wp_nonce_field(basename(__FILE__), 'ap-' . $arguments['name']);
            $input_args = array();
            ?>

            <div class="ap-meta-fields">
                <?php
                foreach ($inputs as $input):


                    $input = array_merge($this->defaults, $input);
                    $input['class'] = (!empty($input['class'])) ? $input['class'] : $arguments['name'] . "_" . $input['name'];
                    $input_args['name'] = $arguments['name'] . "[" . $input['name'] . "]";
                    $input_args['type'] = ($input['type'] != 'repeater') ? $input['type'] : 'hidden';
                    $input_callback = "ap_input_" . $input_args['type'];

                    if (array_key_exists('value', $input)) {
                        $input_args['value'] = $input['value'];
                    }
                    $input_args['id'] = $arguments['name'] . "[" . $input['name'] . "]";
                    $input_args['class'] = (array_key_exists('class', $input)) ? $input['class'] : $input['name'];
                    $input_args['label'] = $input['label'];
                    $input_args['placeholder'] = $input['placeholder'];
                    $input_args['description'] = $input['description'];
                    $input_args['input_option'] = $input['input_option'];


                    if (gettype($value) == "array") {
                        if (array_key_exists($input['name'], $value)) {
                            $input_value = $value[$input['name']];
                        }
                    }else{
                        $input_value =  $input['default'];
                    }

                    ?>
                    <div class="ap-group-child <?php echo esc_attr($input['class']) ?>">
                        <?php

                        echo $this->$input_callback($input_args, $input_value, $object);
                        if ($input['type'] == 'repeater') {
                            $input_repeater_childs = $input['child_inputs'];
                            $this->repeater_field_support($input_value, $input_repeater_childs, $input['class'], $input['label']);

                        }
                        ?>
                        <!-- Display Div-->
                        <div class="ap_display_div <?php echo esc_attr($input['class']) ?>">

                            <?php

                            do_action('ap_display_function_' . $input['name'], $input_value);
                            ?>
                        </div>
                    </div>
                    <?php

                endforeach;
                ?>
            </div>
            <?php

        }

        /**
         * @param $value
         * @param $field_name
         */

        function repeater_field_support($value, $object, $parent_field_name,$parent_label='')
        {
            $repeatr_default = array();
            ?>

            <h3 class="parent_label"><?php echo esc_html($parent_label); ?></h3>
            <div class="ap-meta-repeater" data-value-field= <?php echo esc_attr($parent_field_name); ?>>

                <?php
                if (empty($value)):
                    ?>
                    <div data-repeater-list="<?php echo esc_attr($parent_field_name) . '_parent'; ?>">
                        <div data-repeater-item class="repeater-segment">
                            <?php foreach ($object as $field_name): ?>

                                <?php

                                $field_name = array_merge($this->defaults, $field_name);
                                $input_repeat_args = array(
                                    'name' => $field_name['name']
                                );
                                if ($field_name['default'] !== null) {
                                    $repeatr_default[$field_name['name']] = $field_name['default'];
                                }

                                $input_repeat_args['label'] = $field_name['label'];
                                $input_repeat_args['type'] = $field_name['type'];
                                $input_repeat_args['description'] = $field_name['description'];
                                $input_repeat_args['placeholder'] = $field_name['placeholder'];
                                $input_repeat_args['id'] = $field_name['name'] . '[0]';
                                $input_callback = "ap_input_" . $input_repeat_args['type'];
                                if (array_key_exists('value', $field_name)) {
                                    $input_repeat_args['value'] = $field_name['value'];
                                }

                                $input_repeat_args['class'] = (array_key_exists('class', $field_name)) ? $field_name['class'] : $field_name['name'];

                                ?>

                                <?php echo $this->$input_callback($input_repeat_args, $field_name['default'], array()); ?>
                            <?php endforeach; ?>

                            <input class="repeater-delete-button" data-repeater-delete type="button" value="Delete"/>
                        </div>
                    </div>
                    <input class="repeater-add-button" data-repeater-create type="button" value="Add"
                           data-defaut-value='<?php echo json_encode($repeatr_default); ?>'/>
                    <?php
                else:
                    $encoded_val = json_decode($value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

                    $encoded_vals = $encoded_val [$parent_field_name . '_parent'];

                    $repeat_inc = 0;
                    ?>
                    <div data-repeater-list="<?php echo esc_attr($parent_field_name) . '_parent'; ?>">

                        <?php

                        foreach ($encoded_vals as $name => $get_value):


                            ?>
                            <div data-repeater-item  class="repeater-segment">
                                <?php foreach ($object as $field_name): ?>


                                    <?php

                                    $field_name = array_merge($this->defaults, $field_name);
                                    $input_repeat_args = array(
                                        'name' => $field_name['name']
                                    );

                                    if ($field_name['default'] !== null) {
                                        $repeatr_default[$field_name['name']] = $field_name['default'];
                                    }


                                    $input_repeat_args['type'] = $field_name['type'];
                                    $input_repeat_args['description'] = $field_name['description'];
                                    $input_repeat_args['placeholder'] = $field_name['placeholder'];
                                    $input_repeat_args['id'] = $field_name['name'] . '[' . $repeat_inc . ']';
                                    $input_repeat_args['label'] = $field_name['label'];
                                    $input_callback = "ap_input_" . $input_repeat_args['type'];
                                    if (array_key_exists('value', $field_name)) {
                                        $input_repeat_args['value'] = $field_name['value'];
                                    }

                                    $input_repeat_args['class'] = (array_key_exists('class', $field_name)) ? $field_name['class'] : $field_name['name'];
                                    ?>

                                    <?php echo $this->$input_callback($input_repeat_args, $get_value[$field_name['name']], array()); ?>

                                <?php endforeach; ?>
                                <input class="repeater-delete-button" data-repeater-delete type="button" value="Delete"/>

                            </div>
                            <?php

                            $repeat_inc++;

                        endforeach;
                        ?>


                    </div>

                    <input class="repeater-add-button" data-repeater-create type="button" value="Add"
                           data-defaut-value='<?php echo json_encode($repeatr_default); ?>'/>


                <?php endif; ?>


            </div>
            <?php

        }


        /**
         * @param $post_id
         * @param $post
         * @param $update
         * @return mixed
         */
        function save_custom_meta_box($post_id, $post, $update)
        {

            foreach ($this->set_meta_fields as $field) {

                $meta_post_type = array_key_exists('post_type', (array)$field['post_options']) ? $field['post_options']['post_type'] : 'post';


                if ($post->post_type !== $meta_post_type)
                    continue;

                if (!isset($_POST[$field['id']]) || !wp_verify_nonce($_POST["ap-" . "$field[id]"], basename(__FILE__)))
                    return $post_id;


                if (!current_user_can("edit_post", $post_id))
                    return $post_id;


                if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                    return $post_id;


                $meta_box_text_value = "";
                /*save video meta box*/
                if (isset($_POST[$field['id']])) {
                    $meta_box_text_value = $_POST[$field['id']];
                }
                update_post_meta($post_id, $field['id'], $meta_box_text_value);


            }


            /*end*/
        }


    }
endif;


