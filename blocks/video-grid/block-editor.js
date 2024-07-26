/**
 * WP Video Grid - Block Editor JavaScript
 * 
 * This script handles the Gutenberg block functionality for the WP Video Grid plugin,
 * including block registration, attributes, and preview rendering.
 *
 * @package WP_Video_Grid
 * @version 1.0.0
 */

(function(wp) {
  var registerBlockType = wp.blocks.registerBlockType;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var PanelBody = wp.components.PanelBody;
  var RangeControl = wp.components.RangeControl;
  var SelectControl = wp.components.SelectControl;
  var __ = wp.i18n.__;
  var el = wp.element.createElement;
  var useEffect = wp.element.useEffect;
  var useState = wp.element.useState;

  // Register the WP Video Grid block
  registerBlockType('wp-video-grid/video-grid', {
      title: __('WP Video Grid', 'wp-video-grid'),
      icon: 'grid-view',
      category: 'media',
      attributes: {
          totalVideos: {
              type: 'number',
              default: 6,
          },
          category: {
              type: 'string',
              default: '',
          },
          videosPerGrid: {
              type: 'number',
              default: 3,
          },
          displayType: {
              type: 'string',
              default: 'inline',
          },
      },
      edit: function(props) {
          var attributes = props.attributes;
          var setAttributes = props.setAttributes;
          var [preview, setPreview] = useState('');

          // Fetch preview when attributes change
          useEffect(function() {
              wp.apiFetch({
                  path: '/wp-video-grid/v1/preview',
                  method: 'POST',
                  data: attributes,
              }).then(function(result) {
                  setPreview(result.preview);
              });
          }, [attributes.totalVideos, attributes.category, attributes.videosPerGrid, attributes.displayType]);

          // Attribute change handlers
          function onChangeTotalVideos(newTotal) {
              setAttributes({ totalVideos: newTotal });
          }

          function onChangeCategory(newCategory) {
              setAttributes({ category: newCategory });
          }

          function onChangeVideosPerGrid(newValue) {
              setAttributes({ videosPerGrid: newValue });
          }

          function onChangeDisplayType(newValue) {
              setAttributes({ displayType: newValue });
          }

          // Render block
          return [
              el(InspectorControls, { key: 'inspector' },
                  el(PanelBody, { title: __('Video Grid Settings', 'wp-video-grid') },
                      el(RangeControl, {
                          label: __('Total Videos', 'wp-video-grid'),
                          value: attributes.totalVideos,
                          onChange: onChangeTotalVideos,
                          min: 1,
                          max: 20
                      }),
                      el(SelectControl, {
                          label: __('Video Category', 'wp-video-grid'),
                          value: attributes.category,
                          options: wpVideoGridData.categories,
                          onChange: onChangeCategory
                      }),
                      el(RangeControl, {
                          label: __('Videos Per Grid', 'wp-video-grid'),
                          value: attributes.videosPerGrid,
                          onChange: onChangeVideosPerGrid,
                          min: 1,
                          max: 6
                      }),
                      el(SelectControl, {
                          label: __('Display Type', 'wp-video-grid'),
                          value: attributes.displayType,
                          options: [
                              { label: __('Inline', 'wp-video-grid'), value: 'inline' },
                              { label: __('Popup', 'wp-video-grid'), value: 'popup' },
                          ],
                          onChange: onChangeDisplayType
                      })
                  )
              ),
              el('div', { 
                  className: 'wp-video-grid-editor',
                  dangerouslySetInnerHTML: { __html: preview }
              })
          ];
      },
      save: function() {
          // Rendering in PHP
          return null;
      },
  });
})(window.wp);
