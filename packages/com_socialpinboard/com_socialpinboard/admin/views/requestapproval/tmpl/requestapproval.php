<?php
/**
 * @name        Social Pin Board
 * @version	1.0: memberdetails.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
  * @author      Jeyakiruthika
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$status_value = $this->requestapproval['status_value'];
?>
<form action="index.php?option=com_socialpinboard&layout=requestapproval" method="POST" name="adminForm" id="adminForm">
    <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ ?>
    <div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">

			<input type="text" name="filter_search_approval" id="filter_search_approval" value="<?php echo JRequest::getVar('filter_search_approval'); ?>"  />
			<div class="btn-group pull-right" style="left:5px;">
				<button class="btn tip" type="submit" title="Search"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" title="Clear" onclick="document.id('filter_search_approval').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
		</div>
           <div class="btn-group pull-left" style="left:5px;">
             <select id="sel34L" name="filter_approval" class="inputbox chzn-done" onchange="this.form.submit()">
                 <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>Select Status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>Published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>
	    </select>
         </div>
</div>
    <div class="clearfix"> </div>
    <?php }else if(version_compare(JVERSION, '2.5', 'ge')){?>
    <fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search_approval"><?php echo JText::_('Filter'); ?></label>
			<input type="text" name="filter_search_approval" id="filter_search_approval" value="<?php echo JRequest::getVar('filter_search_approval'); ?>"  />
			<button type="submit"><?php echo JText::_('submit'); ?></button>
			<button type="button" onclick="document.id('filter_search_approval').value='';this.form.submit();"><?php echo JText::_('clear'); ?></button>
		</div>

         <div class="filter-select fltrt">
             <select id="filter_approval" name="filter_approval" class="inputbox" onchange="this.form.submit()">
                 <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>Select Status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>Published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>

	    </select>
         </div>
</fieldset>
     <?php } $pincategory_count= count($this->requestapproval['requestmemberdetails']); ?>
    <table class="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'table table-striped'; } else{ echo 'adminlist'; }?>">
        <thead>
            <tr>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="8%"'; }?>>#</th>

                <th width="10"><input type="checkbox" name="toggle" value="" onclick="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'Joomla.checkAll(this)'; }else{ echo 'checkAll('.$pincategory_count.')' ;}?>" /></th>

                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>><?php echo JHtml::_('grid.sort',  'Id', 'id', $this->requestapproval['order_Dir'], $this->requestapproval['order'] ); ?></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="50%"'; }?>><?php echo JHtml::_('grid.sort',  'Email', 'email_id', $this->requestapproval['order_Dir'], $this->requestapproval['order'] ); ?></th>
                <th  <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="30%" style="text-align:center"'; }?>><?php echo JHtml::_('grid.sort',  'Approval Status', 'approval_status', $this->requestapproval['order_Dir'], $this->requestapproval['order'] ); ?></th>

            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            $i = 0;
            $requestmemberdetails=$this->requestapproval['requestmemberdetails'];

            foreach ($requestmemberdetails as $row)
            {
                $published=JHtml::_('jgrid.published',$row->approval_status,$i);
                $link= JRoute::_( 'index.php?option=com_socialpinboard&layout=requestapproval&task=edit&id[]='. $row->id);
                $checked = JHtml::_('grid.id', $i, $row->id);?>
                <tr class="<?php echo "row$k";?>">
                <td align="center" style="width:50px;"><?php echo $i+1; ?></td>
                <td><?php echo $checked; ?></td>
                <td align="center" ><?php echo ucwords($row->id);?></td>
                <td align="center" ><?php echo $row->email_id;?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; } else { echo 'align="center" style="width:45px;"'; } ?>><?php echo $published; ?></td>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;        }
        ?>
        </tbody>
        <tfoot>
            <td colspan="10"><?php echo $this->requestapproval['pageNav']->getListFooter(); ?></td>
        </tfoot>
    </table>
    <input type="hidden" name="filter_order" value="<?php echo $this->requestapproval['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->requestapproval['order_Dir']; ?>" />
    <input type="hidden" name="option" value="<?php echo JRequest::getVar( 'option' );?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="hidemainmenu" value="0"/>
</form>