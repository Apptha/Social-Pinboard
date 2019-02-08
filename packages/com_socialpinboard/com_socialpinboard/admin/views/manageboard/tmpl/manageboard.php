<?php
/**
 * @name        Social Pin Board
 * @version	1.0: manageboard.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');//echo '<pre>';print_r($this->boards);
$status_value = $this->getPinboard['status_value'];
?>
<form action="index.php?option=com_socialpinboard&layout=manageboard" method="POST" id="adminForm" name="adminForm">
     <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ ?>
      <div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">

			<input type="text" name="filter_search_board" id="filter_search_board" value="<?php echo JRequest::getVar('filter_search_board'); ?>"  />
			<div class="btn-group pull-right" style="left:5px;">
				<button class="btn tip" type="submit" title="Search"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" title="Clear" onclick="document.id('filter_search_board').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
           <div class="btn-group pull-left"            <div class="btn-group pull-left" style="left:5px;">

             <select id="sel34L" name="filter_board" class="inputbox chzn-done" onchange="this.form.submit()">
                 <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>Select Status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>Published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="3" <?php if($status_value == '3'){echo "selected='selected'";JToolbarHelper::deleteList('', 'remove', 'JTOOLBAR_EMPTY_TRASH');} ?>>Trashed</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>
	    </select>

         </div>
</div>
    <div class="clearfix"> </div>
    <?php }else if(version_compare(JVERSION, '2.5', 'ge')){?>
    <fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search_board"><?php echo JText::_('Filter'); ?></label>
			<input type="text" name="filter_search_board" id="filter_search_board" value="<?php echo JRequest::getVar('filter_search_board'); ?>"  />
			<button type="submit"><?php echo JText::_('submit'); ?></button>
			<button type="button" onclick="document.id('filter_search_board').value='';this.form.submit();"><?php echo JText::_('clear'); ?></button>
		</div>
           <div class="filter-select fltrt">
             <select id="filter_board" name="filter_board" class="inputbox" onchange="this.form.submit()">
                 <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>Select Status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>Published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="3" <?php if($status_value == '3'){echo "selected='selected'";JToolbarHelper::deleteList('', 'remove', 'JTOOLBAR_EMPTY_TRASH');} ?>>Trashed</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>

	    </select>
         </div>
</fieldset>
    <?php } $pincategory_count= count($this->getPinboard['manageboard']); ?>
    <table class="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'table table-striped'; } else{ echo 'adminlist'; }?>">
        <thead>
            <tr>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="5%"'; }?>>#</th>
                <th width="10"><input type="checkbox" name="toggle" value="" onclick="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'Joomla.checkAll(this)'; }else{ echo 'checkAll('.$pincategory_count.')' ;}?>" /></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="20%"'; }?>><?php echo JHtml::_('grid.sort',  'Board Name', 'board_name', $this->getPinboard['order_Dir'], $this->getPinboard['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="20%"'; }?>><?php echo JHtml::_('grid.sort',  'User Name', 'username', $this->getPinboard['order_Dir'], $this->getPinboard['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="15%"'; }?>><?php echo JHtml::_('grid.sort',  'Board Description', 'board_description', $this->getPinboard['order_Dir'], $this->getPinboard['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="15%"'; }?>><?php echo JHtml::_('grid.sort',  'Board Category', 'category_name', $this->getPinboard['order_Dir'], $this->getPinboard['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>><?php echo JHtml::_('grid.sort',  'Created Date', 'created_date', $this->getPinboard['order_Dir'], $this->getPinboard['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" style="text-align:center"'; }?>><?php echo JHtml::_('grid.sort',  'Published', 'status', $this->getPinboard['order_Dir'], $this->getPinboard['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; } else { echo 'width="10"'; }?>><?php echo JHtml::_('grid.sort',  'ID', 'board_id', $this->getPinboard['order_Dir'], $this->getPinboard['order'] ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            $i = 0;
            foreach ($this->getPinboard['manageboard'] as $row)
            {
                $published= JHtml::_('jgrid.published',$row->status,$i);
                $link= JRoute::_( 'index.php?option=com_socialpinboard&layout=manageboard&task=edit&cid[]='. $row->board_id);
                $checked = JHtml::_('grid.id', $i, $row->board_id);?>
            <tr class="<?php echo "row$k";?>">
                <td align="center" style="width:50px;"><?php echo $i+1; ?></td>
                <td><?php echo $checked; ?></td>
                <td><?php echo $row->board_name;?></td>
                 <td><?php echo $row->username;?></td>
                <td><?php echo $row->board_description;?></td>
                <td><?php echo $row->category_name;?></td>
                <?php
                $originalDate = $row->created_date;
                $newDate = date("d M Y", strtotime($originalDate));
                ?>
                <td style="width:90px;"><?php echo $newDate;?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; } else { echo 'align="center" style="width:45px;"'; } ?>><?php echo $published; ?></td>
                <td align="center" style="width:90px;"><?php echo $row->board_id;?></td>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;        }
        ?>
        </tbody>
        <tfoot>
            <td colspan="9"><?php echo $this->getPinboard['pageNav']->getListFooter(); ?></td>
        </tfoot>
    </table>
    <input type="hidden" name="filter_order" value="<?php echo $this->getPinboard['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->getPinboard['order_Dir']; ?>" />
    <input type="hidden" name="option" value="<?php echo JRequest::getVar( 'option' );?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="hidemainmenu" value="0"/>
</form>