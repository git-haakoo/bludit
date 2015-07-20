<?php defined('BLUDIT') or die('Bludit CMS.');

// ============================================================================
// Functions
// ============================================================================

function editPage($args)
{
	global $dbPages;
	global $Language;

	// Page status, published or draft.
	if( isset($args['publish']) ) {
		$args['status'] = "published";
	}
	else {
		$args['status'] = "draft";
	}

	if(!isset($args['parent'])) {
		$args['parent'] = NO_PARENT_CHAR;
	}

	// Edit the page.
	if( $dbPages->edit($args) )
	{
		$dbPages->regenerate();

		Alert::set($Language->g('the-changes-have-been-saved'));
		Redirect::page('admin', 'edit-page/'.$args['key']);
	}
	else
	{
		Alert::set($Language->g('an-error-occurred-while-trying-to-edit-the-page'));
	}
}

function deletePage($key)
{
	global $dbPages;
	global $Language;
	
	if( $dbPages->delete($key) )
	{
		Alert::set('The page has been deleted successfully');
		Redirect::page('admin', 'manage-pages');
	}
	else
	{
		Alert::set('an-error-occurred-while-trying-to-delete-the-page');
	}
}

// ============================================================================
// POST Method
// ============================================================================

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	if( isset($_POST['delete']) ) {
		deletePage($_POST['key']);
	}
	else {
		editPage($_POST);
	}
}

// ============================================================================
// Main
// ============================================================================

if(!$dbPages->pageExists($layout['parameters'])) {
	Redirect::page('admin', 'manage-pages');
}

$_Page = $pages[$layout['parameters']];
