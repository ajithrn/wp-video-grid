/**
 * WP Video Grid - Admin JavaScript
 * 
 * This script handles the admin functionality for the WP Video Grid plugin,
 * including video upload, thumbnail selection, and embed checking.
 *
 * @package WP_Video_Grid
 * @version 1.0.0
 */

(function($) {
  $(document).ready(function() {
      // Toggle between upload and external video options
      function toggleVideoContainers() {
          if ($('#video_type').val() === 'upload') {
              $('#video_upload_container').show();
              $('#video_external_container').hide();
          } else {
              $('#video_upload_container').hide();
              $('#video_external_container').show();
          }
      }

      // Initial toggle on page load
      toggleVideoContainers();

      // Toggle between upload and external video options
      $('#video_type').on('change', function() {
          toggleVideoContainers();
      });

      // Initialize media uploader for video file
      $('#upload_video_button').on('click', function(e) {
          e.preventDefault();
          var video_uploader = wp.media({
              title: 'Upload Video',
              button: {
                  text: 'Use this video'
              },
              multiple: false
          }).on('select', function() {
              var attachment = video_uploader.state().get('selection').first().toJSON();
              $('#video_file').val(attachment.url);
          }).open();
      });

      // Initialize media uploader for custom thumbnail
      $('#upload_thumbnail_button').on('click', function(e) {
          e.preventDefault();
          var thumbnail_uploader = wp.media({
              title: 'Upload Thumbnail',
              button: {
                  text: 'Use this image'
              },
              multiple: false
          }).on('select', function() {
              var attachment = thumbnail_uploader.state().get('selection').first().toJSON();
              $('#custom_thumbnail').val(attachment.id);
              if ($('#thumbnail_preview').length) {
                  $('#thumbnail_preview').attr('src', attachment.url);
              } else {
                  $('#thumbnail_preview_container').html('<img src="' + attachment.url + '" id="thumbnail_preview" />');
              }
          }).open();
      });

      // Check embed functionality
      $('#check_embed_button').on('click', function(e) {
          e.preventDefault();
          var videoUrl = $('#video_url').val();
          if (videoUrl) {
              $.ajax({
                  url: ajaxurl,
                  type: 'POST',
                  data: {
                      action: 'wp_video_grid_check_embed',
                      video_url: videoUrl,
                      nonce: wp_video_grid.nonce
                  },
                  success: function(response) {
                      if (response.success) {
                          $('#embed_preview_container').html(response.data.embed);
                      } else {
                          $('#embed_preview_container').html('<p class="error">' + response.data.message + '</p>');
                      }
                  },
                  error: function() {
                      $('#embed_preview_container').html('<p class="error">Error checking embed. Please try again.</p>');
                  }
              });
          } else {
              $('#embed_preview_container').html('<p class="error">Please enter a video URL.</p>');
          }
      });

      // Fetch thumbnail for external videos
      $('#video_url').on('change', function() {
          var videoUrl = $(this).val();
          if (videoUrl) {
              $.ajax({
                  url: ajaxurl,
                  type: 'POST',
                  data: {
                      action: 'wp_video_grid_fetch_thumbnail',
                      video_url: videoUrl,
                      nonce: wp_video_grid.nonce
                  },
                  success: function(response) {
                      if (response.success && response.data.thumbnail_url) {
                          if ($('#thumbnail_preview').length) {
                              $('#thumbnail_preview').attr('src', response.data.thumbnail_url);
                          } else {
                              $('#thumbnail_preview_container').html('<img src="' + response.data.thumbnail_url + '" id="thumbnail_preview" />');
                          }
                      }
                  }
              });
          }
      });
  });
})(jQuery);
