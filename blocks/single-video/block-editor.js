/**
 * WP Video Grid - Single Video Block Editor
 * 
 * This script handles the Gutenberg block functionality for the Single Video block.
 *
 * @package WP_Video_Grid
 * @version 1.4.0
 */

(function(wp) {
  var registerBlockType = wp.blocks.registerBlockType;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var PanelBody = wp.components.PanelBody;
  var ComboboxControl = wp.components.ComboboxControl;
  var SelectControl = wp.components.SelectControl;
  var __ = wp.i18n.__;
  var el = wp.element.createElement;
  var useState = wp.element.useState;
  var useEffect = wp.element.useEffect;
  var useSelect = wp.data.useSelect;

  registerBlockType('wp-video-grid/single-video', {
      title: __('WP Video Grid: Single Video', 'wp-video-grid'),
      icon: 'video-alt3',
      category: 'media',
      attributes: {
          videoId: {
              type: 'number',
          },
          displayType: {
              type: 'string',
              default: 'inline',
          },
      },
      edit: function(props) {
          var attributes = props.attributes;
          var setAttributes = props.setAttributes;
          var [videoOptions, setVideoOptions] = useState([]);
          var [isLoading, setIsLoading] = useState(true);

          useEffect(function() {
              wp.apiFetch({
                  path: '/wp/v2/wp-video-grid?per_page=10&orderby=date&order=desc',
              }).then(function(videos) {
                  setVideoOptions(videos.map(function(video) {
                      return { label: video.title.rendered, value: video.id.toString() };
                  }));
                  setIsLoading(false);
              });
          }, []);

          function onChangeVideo(newVideoId) {
              setAttributes({ videoId: parseInt(newVideoId) });
          }

          function onSearchChange(searchTerm) {
              if (searchTerm.length > 2) {
                  setIsLoading(true);
                  wp.apiFetch({
                      path: '/wp/v2/wp-video-grid?search=' + encodeURIComponent(searchTerm) + '&per_page=10',
                  }).then(function(videos) {
                      setVideoOptions(videos.map(function(video) {
                          return { label: video.title.rendered, value: video.id.toString() };
                      }));
                      setIsLoading(false);
                  });
              }
          }

          var selectedVideo = useSelect(function(select) {
              return attributes.videoId ? select('core').getEntityRecord('postType', 'wp-video-grid', attributes.videoId) : null;
          }, [attributes.videoId]);

          return [
              el(InspectorControls, { key: 'inspector' },
                  el(PanelBody, { title: __('Video Settings', 'wp-video-grid') },
                      el(ComboboxControl, {
                          label: __('Select Video', 'wp-video-grid'),
                          value: attributes.videoId ? attributes.videoId.toString() : '',
                          options: videoOptions,
                          onChange: onChangeVideo,
                          onFilterValueChange: onSearchChange,
                          isLoading: isLoading
                      }),
                      el(SelectControl, {
                          label: __('Display Type', 'wp-video-grid'),
                          value: attributes.displayType,
                          options: [
                              { label: __('Inline', 'wp-video-grid'), value: 'inline' },
                              { label: __('Popup', 'wp-video-grid'), value: 'popup' },
                          ],
                          onChange: function(newDisplayType) {
                              setAttributes({ displayType: newDisplayType });
                          }
                      })
                  )
              ),
              el('div', { className: 'wp-video-grid-single-video-editor' },
                  el(ComboboxControl, {
                      label: __('Search and Select Video', 'wp-video-grid'),
                      value: attributes.videoId ? attributes.videoId.toString() : '',
                      options: videoOptions,
                      onChange: onChangeVideo,
                      onFilterValueChange: onSearchChange,
                      isLoading: isLoading
                  }),
                  attributes.videoId && selectedVideo ? (
                      el('div', { className: 'wp-video-grid-item' },
                          el('div', { className: 'wp-video-grid-item-inner' },
                              el('div', { className: 'thumbnail-container' },
                                  el('img', {
                                      src: selectedVideo.video_thumbnail || '',
                                      alt: selectedVideo.title.rendered || '',
                                      className: 'video-thumbnail'
                                  }),
                                  el('div', { className: 'play-button' })
                              )
                          )
                      )
                  ) : null
              )
          ];
      },
      save: function() {
          // Rendering in PHP
          return null;
      },
  });
})(window.wp);
