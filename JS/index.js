/**
 * General application script
 * Main entry point for application initialization
 */

/**
 * Helper function to format seconds as MM:SS
 * @param {number} seconds - Time in seconds
 * @returns {string} Formatted time
 */
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
}