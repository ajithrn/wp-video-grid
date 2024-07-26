# WordPress Video Grid

## Description

WordPress Video Grid is a WordPress plugin that allows you to create and manage video grids on your WordPress website. It supports both self-hosted videos and external videos from platforms like YouTube and Vimeo. The plugin now includes two main blocks: Video Grid and Single Video.

## Features

- Custom post type for video management
- Support for self-hosted videos and external videos (YouTube, Vimeo)
- Custom taxonomy for video categorization
- Two Gutenberg blocks:
  1. Video Grid: for creating customizable video grids
  2. Single Video: for embedding individual videos with display options
- Customizable grid layout
- Searchable dropdown for easy video selection
- Option for inline or popup video playback
- Lazy loading of video thumbnails
- Responsive design

## Requirements

- WordPress 5.0+
- PHP 7.0+
- Modern web browser

## Installation

1. Upload the `wp-video-grid` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start adding videos through the 'WP Videos Grid' menu in your WordPress admin panel

## Usage

### Adding Videos

1. Go to 'WP Videos Grid' in your WordPress admin menu
2. Click 'Add New'
3. Enter a title for your video
4. Choose the video source (Upload or External)
5. Upload a video file or enter a YouTube/Vimeo URL
6. Set a custom thumbnail (optional)
7. Assign categories if needed
8. Publish your video

### Creating a Video Grid

1. Create a new post or page
2. Add the 'WP Video Grid' block
3. Adjust the block settings as needed:
   - Total Videos: Set the number of videos to display
   - Video Category: Choose a specific category or show all
   - Videos Per Grid: Set the number of columns
   - Display Type: Choose between inline or popup playback
4. Publish or update your post/page

### Embedding a Single Video

1. Create a new post or page
2. Add the 'WP Video Grid: Single Video' block
3. Use the searchable dropdown to select a video
4. Choose the display type (inline or popup)
5. Publish or update your post/page

## Frequently Asked Questions

**Q: What video formats are supported for self-hosted videos?**
A: The plugin supports all video formats that are natively supported by WordPress, including MP4, WebM, and OGV.

**Q: Can I use videos from other platforms besides YouTube and Vimeo?**
A: The plugin currently supports YouTube and Vimeo. Support for other platforms may be added in future updates.

**Q: How do I customize the appearance of the video grid or single video?**
A: You can use custom CSS to style the video grid and single video blocks. The plugin provides CSS classes that you can target for customization.

## Changelog

Please see the [CHANGELOG.md](CHANGELOG.md) file for a detailed list of changes and version history.

## Support

For support, please create an issue in the plugin's GitHub repository or contact the plugin author.

## License

This plugin is licensed under the GPL v2 or later.
