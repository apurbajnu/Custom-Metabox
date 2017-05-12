(function ($) {
    'use strict';

    $(document).ready(function () {




        function buildRuleset() {
            var ruleset = $.deps.createRuleset();
            var rulesetContainer = {};
            $.each(dependancy_meta,function(a,b){

                var inputClass = b.name ;
                var tginputClass = b.target ;
                var inputValue =  b.value;



                var bName =  b.name.replace(".","");
                var innerClass = $(inputClass).find('input');

               if(innerClass.length>0){
                   if(b.sub == null){
                       rulesetContainer[bName] = ruleset.createRule(innerClass,'==',inputValue);
                       rulesetContainer[bName].include(tginputClass);
                   }else{
                       rulesetContainer[bName] = rulesetContainer[b.sub].createRule(innerClass,'==',inputValue);
                       rulesetContainer[bName].include(tginputClass);
                   }
               }

            });

            ruleset.install();

        }

        buildRuleset();


     






        $('.color-metabox').find('input').wpColorPicker();

        $('.gallery-image-metabox').each(function(){
            var frame,
                savedImages=[],
                generatedHtml = '',
                savedValue = '' ,
                numberOfImages,
                imgval = [] ,
                metaBox = $(this).children('div').eq(0), // Your meta box id here
                addImgLink = metaBox.find('.upload-custom-img'),
                delImgLink = metaBox.find( '.delete-custom-img'),
                imgContainer = metaBox.find( '.custom-img-container'),
                imgIdInput = metaBox.find( 'input' );
            if ( imgIdInput.val()){
                savedImages = JSON.parse(imgIdInput.val());
            }


            // ADD IMAGE LINK
            addImgLink .on( 'click', function( event ){

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( frame ) {
                    frame.open();
                    return;
                }

                // Create a new media frame

                frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image to upload',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: true,
                    library: { type : 'image' },// Set to true to allow multiple files to be selected
                });

                frame.on('open',function() {

                    var selection = frame.state().get('selection');


                    savedImages.forEach(function(pf) {

                        var attachment = wp.media.attachment(pf.id);
                        attachment.fetch();

                        attachment.set({
                            'mipfLink': pf.link,
                            'mipfTitle': pf.title
                        });

                        selection.add( attachment ? [ attachment ] : [] );

                    });
                });


                // When an image is selected in the media frame...
                frame.on( 'select', function() {

                    // Get media attachment details from the frame state
                    var generatedHtml = '';
                    var attachment = frame.state().get('selection').toJSON();
                    numberOfImages = attachment.length;
                    attachment.forEach(function(object, index){
                        generatedHtml += '<div class="img-container" style="width:32%"><img src="'+object.url+'" alt="" /></div>';

                        imgval.push({
                            id: object.id,
                            url: object.url,
                            title: object.title
                        })
                        // Send the attachment id to our hidden input

                    })

                    imgContainer.empty().append( generatedHtml );

                    imgIdInput.val(JSON.stringify(imgval));
                    // Hide the add image link
                    addImgLink.addClass( 'hidden' );

                    // Unhide the remove image link
                    delImgLink.removeClass( 'hidden' );
                    var selection = frame.state().get('selection');
                    var selected = ''; // the id of the image

                    selection.add(wp.media.attachment(selected));


                });

                // Finally, open the modal on click
                frame.open();


            });


            // DELETE IMAGE LINK
            delImgLink.on( 'click', function( event ){

                event.preventDefault();

                // Clear out the preview image
                imgContainer.html( '' );

                // Un-hide the add image link
                addImgLink.removeClass( 'hidden' );

                // Hide the delete image link
                delImgLink.addClass( 'hidden' );

                // Delete the image id from the hidden input
                imgIdInput.val( '' );
                savedImages = [];
                imgval = [];


            });

            $(window).on('load',function () {

                numberOfImages = savedImages.length;
                if(numberOfImages>0){
                    // Un-hide the add image link
                    addImgLink.removeClass('hidden');

                    // Hide the delete image link
                    delImgLink.removeClass('hidden');

                }


                savedImages.forEach(function (object) {

                    savedValue += '<div class="img-container" style="width:32%" > <img  src="'+object.url+'" alt="" /></div>';
                })
                imgContainer.append( savedValue );
            })



        })// end of gallery

        var mi_meta_repeater = $('.ap-meta-repeater');



        mi_meta_repeater.each(
            function (a,b) {
                var self = $(b);
                var addButton = self.children('.repeater-add-button');
                var defaults = JSON.parse( addButton.attr('data-defaut-value') );

                self.repeater(
                    {

                        defaultValues: defaults,

                        show: function () {
                            $(this).slideDown();
                            $(this).find('label').attr('for', $(this).find('input').attr('id'));
                        },

                        hide: function (deleteElement) {

                            if (confirm('Are you sure you want to delete this element?')) {

                                var parent = $(this).parent().parent();

                                $(this).slideUp(function () {

                                    $(this).remove();

                                    /*get the Group name of reapeater value*/
                                    var repeaterGroupname = $(this).parent().data('repeater-list');
                                    /*repeater value array*/
                                    var repeaterArray = parent.repeaterVal();
                                    /*Deleted Item index*/
                                    var elementIndex = $(this).index();

                                    var hiddenField = parent.data('value-field');

                                    $('#'+hiddenField).val(JSON.stringify(repeaterArray));

                                });
                            }
                        },


                        isFirstItemUndeletable: true
                    }
                );

                self.delegate('input,select,textarea','change',function () {
                    var  parent =  self;
                    var hiddenField = parent.data('value-field');
                    var repeaterVal = parent.repeaterVal();
                    $('#'+hiddenField).val(JSON.stringify(repeaterVal));
                })

                self.delegate(addButton,'click',function () {
                    var  parent =  self;
                    var hiddenField = parent.data('value-field');
                    var repeaterVal = parent.repeaterVal();
                    $('#'+hiddenField).val(JSON.stringify(repeaterVal));
                })


            }
        )


    })// end of ready

})(jQuery)

