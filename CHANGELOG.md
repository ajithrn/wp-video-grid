# Changelog

## [1.0.0] - 2024-07-21

### Added
- Initial release of WordPress Video Grid plugin
- Custom post type for video management
- Support for self-hosted videos and external videos (YouTube, Vimeo)
- Custom taxonomy for video categorization
- Gutenberg block for easy grid creation
- Customizable grid layout
- Lazy loading of video thumbnails
- Popup or inline video playback
- Responsive design

## [1.1.0] - 2024-07-22

### Added
- Option to remove custom thumbnail from the backend
- "Remove Thumbnail" button in the video edit screen
- Functionality to clear custom thumbnail data when removed

### Changed
- Updated MetaBoxes.php to include remove thumbnail option
- Modified admin.js to handle custom thumbnail removal
- Improved save_meta_boxes method to properly handle thumbnail removal

### Documentation
- Added system requirements to README