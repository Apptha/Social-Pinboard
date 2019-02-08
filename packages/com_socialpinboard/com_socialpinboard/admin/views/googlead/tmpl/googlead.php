<?php
/**
 * @name        Social Pin Board
 * @version	1.0: pincategory.php$
 * @since       Joomla 1.5&1.6&1.7
 * @package	apptha
 * @subpackage	com_socialpinboard
 * @author      Contus Support
 * @copyright   Copyright (C) 2011 powered by Apptha
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
$status_value = $this->getGooglead['status_value'];
?>
<form id="adminForm" action="index.php?option=com_socialpinboard&layout=googlead" method="POST" name="adminForm">
    <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ ?>
     <div id="filter-bar" class="btn-toolbar">

           <div class="btn-group pull-left" style="left:5px;">
             <select id="sel34L" name="filter_google" class="inputbox chzn-done" onchange="this.form.submit()">
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
         <div class="filter-select fltrt">
             <select id="filter_category" name="filter_google" class="inputbox" onchange="this.form.submit()">
                 <option value="0" <?php if($status_value == '0'){echo "selected='selected'";} ?>>Select Status</option>
                 <option value="1" <?php if($status_value == '1'){echo "selected='selected'";} ?>>Published</option>
                 <option value="2" <?php if($status_value == '2'){echo "selected='selected'";} ?>>Unpublished</option>
                 <option value="4" <?php if($status_value == '4'){echo "selected='selected'";} ?>>All</option>

	    </select>
         </div>
</fieldset>
        <?php } $pincategory_count= count($this->getGooglead['pin_googlead']); ?>
    <table class="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'table table-striped'; } else{ echo 'adminlist'; }?>">
        <thead>
            <tr>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="8%"'; }?>>#</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }else { echo 'width="10"'; } ?>><input type="checkbox" name="toggle" value="" onclick="<?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'Joomla.checkAll(this)'; }else{ echo 'checkAll('.$pincategory_count.')' ;}?>" /></th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" align="left"'; }?> >Ad Client</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>>Ad Slot</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>>Width</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>>Height</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>>Position</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%"'; }?>>Created Date</th>
                <th <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'width="10%" style="text-align:center"'; }?>>Published</th>
                <th width="10">ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            $i = 0;
            foreach ( $this->getGooglead['pin_googlead'] as $row)
            {
                $published=JHtml::_('jgrid.published',$row->status,$i);
                $link= JRoute::_( 'index.php?option=com_socialpinboard&layout=googlead&task=edit&cid[]='. $row->ad_id);
                $checked = JHtml::_('grid.id', $i, $row->ad_id);?>
            <tr class="<?php echo "row$k";?>">
                <td align="center" style="width:20px;"><?php echo $i+1; ?></td>
                <td align="center"><?php echo $checked; ?></td>
                <td style="width:250px;"><a href="<?php echo $link;?>"><?php echo $row->adclient;?></a></td>
                <td align="center" style="width:50px;"><?php echo $row->adslot;?></td>
                <td align="center" style="width:50px;"><?php echo $row->adwidth;;?></td>
                <td align="center" style="width:50px;"><?php echo $row->adheight;;?></td>
                <?php
                $originalDate = $row->created_date;
                $newDate = date("d M Y", strtotime($originalDate));

                ?>
                <td align="center" style="width:50px;"><?php echo ucfirst(str_replace('_', ' ', $row->pin_ad_position));?></td>
                <td align="center" style="width:50px;"><?php echo $newDate;?></td>
                <td <?php if(version_compare(JVERSION, '3.0.0', 'ge')){ echo 'style="text-align:center"'; } else { echo 'align="center" style="width:35px;"'; } ?>><?php echo $published; ?></td>
                <td align="center" style="width:30px;"><?php echo $row->ad_id;?></td>
            </tr>
            <?php
            $k = 1 - $k;
            $i++;        }
        ?>
        </tbody>
        <tfoot>
            <td colspan="10"><?php echo $this->getGooglead['pageNav']->getListFooter(); ?></td>
        </tfoot>
    </table>

    <input type="hidden" name="option" value="<?php echo JRequest::getVar( 'option' );?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="hidemainmenu" value="0"/>
</form>