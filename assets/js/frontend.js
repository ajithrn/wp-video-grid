/**
 * WP Video Grid - Frontend JavaScript
 * 
 * This script handles the frontend functionality for the WP Video Grid plugin,
 * including video playback, popup handling, and lazy loading of thumbnails.
 *
 * @package WP_Video_Grid
 * @version 1.4.0
 */

(function($) {
  $(document).ready(function() {
      // Handle click event on play button
      $('.wp-video-grid-item').on('click', '.play-button', function(e) {
          e.preventDefault();
          var $item = $(this).closest('.wp-video-grid-item');
          var videoUrl = $item.data('video-url');
          var displayType = $item.data('display-type');
          var videoType = $item.data('video-type');

          // Add loading spinner
          if (!$item.find('.loading-spinner').length) {
              $item.append('<div class="loading-spinner"></div>');
          }

          // Add loading class
          $item.addClass('loading');

          if (videoType === 'external') {
              // For external videos, get the embed code and play
              $.ajax({
                  url: wp_video_grid.ajaxurl,
                  type: 'POST',
                  data: {
                      action: 'wp_video_grid_get_embed',
                      video_url: videoUrl,
                      nonce: wp_video_grid.nonce
                  },
                  success: function(response) {
                      handleVideoEmbed(response, $item, displayType);
                  },
                  error: function() {
                      console.error('Ajax error occurred while fetching video embed.');
                      $item.removeClass('loading');
                  }
              });
          } else {
              // For self-hosted videos
              var videoHtml = '<div class="wp-video-grid-responsive-embed"><video src="' + videoUrl + '" controls style="width: 100%; height: 100%;" autoplay muted></video></div>';
              handleVideoEmbed({ success: true, data: { embed: videoHtml } }, $item, displayType);
          }
      });

      // Function to handle video embed response
      function handleVideoEmbed(response, $item, displayType) {
          if (response.success) {
              var $embed = $(response.data.embed);
              
              // Handle iframe (e.g., YouTube, Vimeo)
              var $iframe = $embed.find('iframe');
              if ($iframe.length) {
                  var src = $iframe.attr('src');
                  if (src) {
                      // Remove any existing autoplay parameter
                      src = src.replace(/(&|\?)autoplay=\d+/g, '');
                      // Add autoplay and mute parameters
                      src += (src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1&mute=1';
                      $iframe.attr('src', src);
                  }
              }

              // Handle HTML5 video
              var $video = $embed.find('video');
              if ($video.length) {
                  $video.attr('autoplay', 'autoplay').prop('muted', true);
              }

              if (displayType === 'inline') {
                  $item.find('.thumbnail-container').empty().append($embed);
                  setTimeout(function() {
                      $item.removeClass('loading').addClass('loaded');
                      // For HTML5 video, attempt to play
                      if ($video.length) {
                          $video[0].play().catch(function(error) {
                              console.error('Autoplay failed:', error);
                          });
                      }
                  }, 2000);
              } else if (displayType === 'popup') {
                  showPopup($embed);
              }
          } else {
              console.error('Error loading video:', response.data.message);
              $item.removeClass('loading');
          }
      }

      // Function to show video popup
      function showPopup(content) {
          var $popup = $('<div class="wp-video-grid-popup"></div>');
          var $popupInner = $('<div class="wp-video-grid-popup-inner"></div>');
          var $closeButton = $('<button class="close-popup" aria-label="Close">&times;</button>');
          
          $popupInner.html(content);
          $popupInner.append($closeButton);
          $popup.append($popupInner);
          $('body').append($popup);
          
          // Add a slight delay before showing the popup to allow for smooth animation
          setTimeout(function() {
              $popup.addClass('active');
          }, 50);
          
          $popup.on('click', function(e) {
              if ($(e.target).hasClass('wp-video-grid-popup') || $(e.target).hasClass('close-popup')) {
                  closePopup($popup);
              }
          });
      }

      // Function to close video popup
      function closePopup($popup) {
          $popup.removeClass('active');
          setTimeout(function() {
              $popup.remove();
              // Remove loading spinner and class from all items
              $('.wp-video-grid-item').removeClass('loading').find('.loading-spinner').remove();
          }, 300);
      }

      // Lazy load thumbnails
      function lazyLoadThumbnails() {
          $('.video-thumbnail').each(function() {
              var $img = $(this);
              if ($img.attr('data-src') && !$img.attr('src')) {
                  if (isElementInViewport($img[0])) {
                      $img.attr('src', $img.data('src'));
                      $img.removeAttr('data-src');
                  }
              }
          });
      }

      // Helper function to check if an element is in the viewport
      function isElementInViewport(el) {
          var rect = el.getBoundingClientRect();
          return (
              rect.top >= 0 &&
              rect.left >= 0 &&
              rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
              rect.right <= (window.innerWidth || document.documentElement.clientWidth)
          );
      }

      // Call lazy load function on scroll and page load
      $(window).on('scroll', lazyLoadThumbnails);
      lazyLoadThumbnails();

      // Add touch support for mobile devices
      $('.wp-video-grid-item').on('touchstart', function() {
          $(this).addClass('touch-hover');
      }).on('touchend', function() {
          $(this).removeClass('touch-hover');
      });
  });
})(jQuery);
