# Changelog

## [1.5.1] - 2024-07-30

### Changed
- Disabled block editor (Gutenberg) for WP Video Grid custom post type

### Improved
- Streamlined video management experience by using classic editor interface


## [1.5.0] - 2024-07-27

### Added
- New `render_video_item` function in the Frontend class for centralized video item rendering
- Added .gitignore file

### Changed
- Refactored Video Grid Block to use the new `render_video_item` function
- Updated Single Video Block to utilize the `render_video_item` function
- Improved code reusability and consistency in video item rendering across the plugin

### Improved
- Centralized video item rendering logic for easier maintenance and updates


## [1.4.0] - 2024-07-27

### Added
- New "WP Video Grid Single Video" block for selecting and displaying individual videos
- Dropdown with search functionality for video selection in the Single Video block
- Option to choose between inline and popup display for single video block

### Changed
- Removed featured image support from the custom post type
- Reused thumbnail logic from video grid block for consistency

### Fixed
- Fixed REST API route issues for the custom post type

### Improved
- Enhanced code organization and reusability
- Optimized video loading in the editor for faster performance


## [1.3.0] - 2024-07-27

### Changed
- Restructured the plugin to improve organization and maintainability
- Moved block-related code to a dedicated 'blocks/video-grid' directory
- Consolidated block-specific styles into the main CSS file

### Improved
- Enhanced code organization for better future maintenance


## [1.2.0] - 2024-07-24

### Added
- New settings page to manage plugin data deletion preference
- Option to keep or delete plugin data upon uninstallation
- "Settings" link in the plugin's action links on the plugins listing page

### Changed
- Uninstall process now respects user preference for data deletion
- Default setting for data deletion is now 'keep data' (unchecked)

## [1.1.1] - 2024-07-23'

### Added
- Display a default message when no videos are available in the video grid block
- Provide instructions for users on how to add videos when the grid is empty
- Add direct links to 'Add New Video' and 'Manage Categories' pages in the empty state message

### Improved
- User experience by guiding users to add content when the video grid is empty
- Block editor preview to show helpful message when no videos are found


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
