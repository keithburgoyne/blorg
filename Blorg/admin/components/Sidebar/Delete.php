<?php

require_once 'SwatDB/SwatDB.php';
require_once 'SwatI18N/SwatI18NLocale.php';
require_once 'Admin/pages/AdminDBDelete.php';
require_once 'Admin/AdminListDependency.php';
require_once 'Blorg/BlorgGadgetFactory.php';
require_once 'Blorg/dataobjects/BlorgGadgetInstanceWrapper.php';

/**
 * Delete confirmation page for sidebar gadgets
 *
 * @package   Blörg
 * @copyright 2008 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 */
class BlorgSidebarDelete extends AdminDBDelete
{
	// init phase
	// {{{ protected function processDBData()

	protected function processDBData()
	{
		parent::processDBData();

		$sql = sprintf('delete from BlorgGadgetInstance
			where id in (%s) and instance %s %s',
			$this->getItemList('integer'),
			SwatDB::equalityOperator($this->app->getInstanceId()),
			$this->app->db->quote($this->app->getInstanceId(), 'integer'));

		$num = SwatDB::exec($this->app->db, $sql);

		$locale = SwatI18NLocale::get();
		$message = new SwatMessage(sprintf(Blorg::ngettext(
			'One sidebar gadget has been deleted.',
			'%s sidebar gadgets have been deleted.', $num),
			$locale->formatNumber($num)));

		$this->app->messages->add($message);
	}

	// }}}

	// build phase
	// {{{ protected function buildInternal()

	protected function buildInternal()
	{
		parent::buildInternal();

		$sql = sprintf('select id, gadget from BlorgGadgetInstance
			where id in (%s) and instance %s %s
			order by displayorder',
			$this->getItemList('integer'),
			SwatDB::equalityOperator($this->app->getInstanceId()),
			$this->app->db->quote($this->app->getInstanceId(), 'integer'));

		$gadget_instances = SwatDB::query($this->app->db, $sql,
			SwatDBClassMap::get('BlorgGadgetInstanceWrapper'));

		$titles = array();
		foreach ($gadget_instances as $gadget_instance) {
			$gadget = BlorgGadgetFactory::get($this->app, $gadget_instance);
			$titles[] = $gadget->getTitle();
		}

		ob_start();

		$h3_tag = new SwatHtmlTag('h3');
		$h3_tag->setContent(Blorg::ngettext(
			'Delete the following sidebar gadget?',
			'Delete the following sidebar gadgets?',
			$this->getItemCount()));

		$h3_tag->display();

		echo '<ul>';

		foreach ($titles as $title) {
			$li_tag = new SwatHtmlTag('li');
			$li_tag->setContent($title);
			$li_tag->display();
		}

		echo '</ul>';

		$message = $this->ui->getWidget('confirmation_message');
		$message->content = ob_get_clean();
		$message->content_type = 'text/xml';
	}

	// }}}
}

?>
