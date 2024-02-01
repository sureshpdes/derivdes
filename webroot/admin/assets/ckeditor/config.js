/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.extraPlugins = 'wordcount';
	config.wordcount = {

	// Whether or not you want to show the Word Count
	showWordCount: true,

	// Whether or not you want to show the Char Count
	showCharCount: true
	};
};
