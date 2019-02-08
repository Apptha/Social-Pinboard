<?php
/**
 * @name        Social Pin Board
 * @version	1.0: managepins.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$status_value = $this->getPins['status_value'];
?>
<form action="index.php?option=com_socialpinboard&layout=managepins" method="POST" name="adminForm" id="adminForm">
     <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ ?>
    <div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">

			<input type="text" name="filter_search_pin" id="filter_search_pin" value="<?php echo JRequest::getVar('filter_search_pin'); ?>"  />
			<div class="btn-group pull-right" style="left:5px;">
				<button class="btn tip" type="submit" title="Search"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" title="Clear" onclick="document.id('filter_search_pin').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
           <div class="btn-group pull-left" style="left:5px;">
             <select id="sel34L" name="filter_pins" class="inputbox chzn-done" onchange="this.form.submit()">
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
			<label class="filter-search-lbl" for="filter_search_pin"><?php echo JText::_('Filter'); ?>:</label>
			<input type="text" name="filter_search_pin" id="filter_search_pin" value="<?php echo JRequest::getVar('filter_search_pin'); ?>"  />
			<button type="submit"><?php echo JText::_('submit'); ?></button>
			<button type="button" onclick="document.id('filter_search_pin').value='';this.form.submit();"><?php echo JText::_('clear'); ?></button>
		</div>


          <div class="filter-select fltrt">
             <select id="filter_pins" name="filter_pins" class="inputbox" onchange="this.form.submit()">
                <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>Select Status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>Published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="3" <?php if($status_value == '3'){echo "selected='selected'";JToolbarHelper::deleteList('', 'remove', 'JTOOLBAR_EMPTY_TRASH');} ?>>Trashed</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>
	    </select>
         </div>


</fieldset>
    <?php } $pincategory_count= count($this->getPins['managepins']); ?>
    <table class="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'table table-striped'; } else{ echo 'adminlist'; }?>">
        <thead>
            <tr>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="3%"'; }?>>#</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="6%"'; }?>><input type="checkbox" name="toggle" value="" onclick="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'Joomla.checkAll(this)'; }else{ echo 'checkAll('.$pincategory_count.')' ;}?>" /></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="20%"'; }?>><?php echo JHtml::_('grid.sort',  'Pin Description', 'pin_description', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>><?php echo JHtml::_('grid.sort',  'Pinner Name', 'username', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>><?php echo JHtml::_('grid.sort',  'Board Name', 'board_name', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>><?php echo JHtml::_('grid.sort',  'Category', 'category_name', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" style="text-align:center"'; }?>><?php echo JHtml::_('grid.sort',  'Repin', 'pin_repin_count', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" style="text-align:center"'; }?>><?php echo JHtml::_('grid.sort',  'Likes', 'pin_likes_count', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" style="text-align:center"'; }?>><?php echo JHtml::_('grid.sort',  'Comments', 'pin_comments_count', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" style="text-align:center"'; }?>><?php echo JHtml::_('grid.sort',  'Published', 'status', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="5%"'; } else { echo 'width="10"'; } ?>><?php echo JHtml::_('grid.sort',  'ID', 'pin_id', $this->getPins['order_Dir'], $this->getPins['order'] ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            $i = 0;
            foreach ($this->getPins['managepins'] as $row)
            {
                $published=JHTML::_('jgrid.published',$row->status,$i);
                $link= JRoute::_( 'index.php?option=com_socialpinboard&layout=managepins&task=edit&cid[]='. $row->pin_id);
                $checked = JHTML::_('grid.id', $i, $row->pin_id);?>
            <tr class="<?php echo "row$k";?>">
                <td align="center" style="width:50px;"><?php echo $i+1; ?></td>
                <td align="center"><?php echo $checked; ?></td>
                <td><?php echo ucwords($row->pin_description);?></td>
                <td><?php echo $row->username;?></td>
                <td><?php echo ucwords($row->board_name);?></td>
                <td><?php echo ucwords($row->category_name);?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; } else { echo 'align="center" style="width:45px;"'; } ?> ><?php echo $row->pin_repin_count; ?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; } else { echo 'align="center" style="width:90px;"'; } ?>><?php echo $row->pin_likes_count;?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; } else { echo 'align="center" style="width:70px;"'; } ?>><?php echo $row->pin_comments_count; ?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; } else { echo 'align="center" style="width:45px;"'; } ?>><?php echo $published; ?></td>
                <td align="center" style="width:90px;"><?php echo $row->pin_id;?></td>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;        }
        ?>
        </tbody>
        <tfoot>
            <td colspan="10"><?php echo $this->getPins['pageNav']->getListFooter(); ?></td>
        </tfoot>
    </table>
    <input type="hidden" name="filter_order" value="<?php echo $this->getPins['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->getPins['order_Dir']; ?>" />
    <input type="hidden" name="option" value="<?php echo JRequest::getVar( 'option' );?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="hidemainmenu" value="0"/>
</form>