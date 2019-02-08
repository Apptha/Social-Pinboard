<?php
/**
 * @name        Social Pin Board
 * @version	1.0: memberdetails.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$status_value = $this->memberdetails['status_value'];
?>
<form action="index.php?option=com_socialpinboard&layout=memberdetails" method="POST" id="adminForm" name="adminForm">
<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ ?>
    <div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">

			<input type="text" name="filter_search_member" id="filter_search_member" value="<?php echo JRequest::getVar('filter_search_member'); ?>"  />
			<div class="btn-group pull-right" style="left:5px;">
				<button class="btn tip" type="submit" title="Search"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" title="Clear" onclick="document.id('filter_search_member').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>

</div>
    <div class="btn-group pull-left" style="left:335px;margin-top:-40px">
             <select id="sel34L" name="filter_memberdetail" class="inputbox chzn-done" onchange="this.form.submit()">
                 <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>Select Status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>Published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="3" <?php if($status_value == '3'){echo "selected='selected'";JToolbarHelper::deleteList('', 'remove', 'JTOOLBAR_EMPTY_TRASH');} ?>>Trashed</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>
	    </select>
         </div>
    <?php } else if(version_compare(JVERSION, '2.5', 'ge')) {?>
    <fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search_member"><?php echo JText::_('Filter'); ?></label>
			<input type="text" name="filter_search_member" id="filter_search_member" value="<?php echo JRequest::getVar('filter_search_member'); ?>"  />
			<button type="submit"><?php echo JText::_('submit'); ?></button>
			<button type="button" onclick="document.id('filter_search_member').value='';this.form.submit();"><?php echo JText::_('clear'); ?></button>
		</div>
               <div class="filter-select fltrt">
             <select id="filter_approval" name="filter_memberdetail" class="inputbox" onchange="this.form.submit()">
                 <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>select status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="3" <?php if($status_value == '3'){echo "selected='selected'";JToolbarHelper::deleteList('', 'remove', 'JTOOLBAR_EMPTY_TRASH');} ?>>Trashed</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>
	    </select>
             </div>

</fieldset>
        <?php } $member_count=count($this->memberdetails['memberdetails']); ?>
    <table class="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'table table-striped'; } else{ echo 'adminlist'; }?>">
        <thead>
            <tr>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="1%"'; }?>>#</th>
                <th  <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="1%"'; }else{ echo 'width="10"'; }?>><input type="checkbox" name="toggle" value="" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'onclick="Joomla.checkAll(this)"'; }else{ echo 'onclick="checkAll('.$member_count.')"'; }?>  /></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="15%" align="center"'; }?>><?php echo JHtml::_('grid.sort',  'Username', 'name', $this->memberdetails['order_Dir'], $this->memberdetails['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" align="left"'; }?>><?php echo JHtml::_('grid.sort',  'First Name', 'first_name', $this->memberdetails['order_Dir'], $this->memberdetails['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" align="left"'; }?>><?php echo JHtml::_('grid.sort',  'Last Name', 'last_name', $this->memberdetails['order_Dir'], $this->memberdetails['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="20%"'; }?>>About</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="20%"'; }?>><?php echo JHtml::_('grid.sort',  'Email', 'a.email', $this->memberdetails['order_Dir'], $this->memberdetails['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" style="text-align:center"'; }?>><?php echo JHtml::_('grid.sort',  'Published', 'block', $this->memberdetails['order_Dir'], $this->memberdetails['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>>ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            $i = 0;
            foreach ($this->memberdetails['memberdetails'] as $row)
            {
                $published=JHtml::_('jgrid.published',!$row->block,$i);
                $link= JRoute::_( 'index.php?option=com_socialpinboard&layout=memberdetails&task=edit&cid[]='. $row->id);
                $checked = JHtml::_('grid.id', $i, $row->id);?>
            <tr class="<?php echo "row$k";?>">
                <td align="center" <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="3%"'; }else{ echo 'style="width:50px;"'; }?> ><?php echo $i+1; ?></td>
                <td><?php echo $checked; ?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'align="center"'; }?>><?php echo ucwords($row->name);?></td>
                <td><?php echo ucwords($row->first_name);?></td>
                <td><?php echo ucwords($row->last_name);?></td>
                <td><?php echo ucwords($row->about);?></td>
                <td style="width:45px;"><?php echo $row->email; ?></td>
                <td  <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; }else{ echo 'align="center" style="width:45px;"'; }?> ><?php echo $published; ?></td>
                <td align="center" style="width:90px;"><?php echo $row->id;?></td>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;        }
        ?>
        </tbody>
        <tfoot>
            <td colspan="10"><?php echo $this->memberdetails['pageNav']->getListFooter(); ?></td>
        </tfoot>
    </table>
    <input type="hidden" name="filter_order" value="<?php echo $this->memberdetails['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->memberdetails['order_Dir']; ?>" />

    <input type="hidden" name="option" value="<?php echo JRequest::getVar( 'option' );?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="hidemainmenu" value="0"/>
</form>