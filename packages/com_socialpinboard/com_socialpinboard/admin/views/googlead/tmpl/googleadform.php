<?php
/**
 *  @Created By   : Durgadevi.M
 *  @Created On   : feb 13 2012
 *  @Purpose      : Adding the new category for pinterest
 **/
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {if(task=='cancel')
            {
                 Joomla.submitform(task, document.getElementById('adminForm'));

            }
            
         if(task=='apply' || task=='save'){

                         var ad_name = document.getElementById('adslot').value;
                         var adclient=document.getElementById('adclient').value
                         var adwidth=document.getElementById('adwidth').value
                         var adheight=document.getElementById('adheight').value
                         
             if(ad_name!=''||adclient!='' ||adwidth!='' || adheight !='' )
                 {
                    
                 }if(adclient=='')
                     {
                         alert('Please Enter Ad Client Value!');
                         return false;
                     }  else if(ad_name=='')
                     {
                         alert('Please Enter Ad Slot Value!');
                         return false;
                     }
                   else  if(adwidth=='')
                     {
                         alert('Please Enter Ad Width Value!');
                         return false;
                     }
                    else if(adheight=='')
                     {
                         alert('Please Enter Ad Height Value!');
                         return false;
                     }else
                         {
                              Joomla.submitform(task, document.getElementById('adminForm'));
                         }
                 
                     }


    }
</script>
<fieldset class="adminform">
    <legend>Google Adsense</legend>
    <table class="admintable">
        <form action="index.php?option=com_socialpinboard&layout=googlead" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data">

            <tr>
                <td>
                    Ad Client
                </td>
                <td>
                    <input type="text" name="adclient" id="adclient" value="<?php if(isset ($this->editPinad->adclient)) { echo $this->editPinad->adclient; }?>" />
                </td>
            </tr>
            <tr>
                <td>
                    Ad Slot
                </td>
                <td>
                    <input type="text" name="adslot" id="adslot" value="<?php if(isset ($this->editPinad->adslot)) { echo $this->editPinad->adslot; }?>" />
                </td>
            </tr>
            <tr>
                <td>
                   Width
                </td>
                <td>
                    <input type="text" name="adwidth" id="adwidth" value="<?php if(isset ($this->editPinad->adwidth)) { echo $this->editPinad->adwidth; }?>" />
                </td>
            </tr>
            <tr>
                <td>
                   Height
                </td>
                <td>
                    <input type="text" name="adheight" id="adheight" value="<?php if(isset ($this->editPinad->adheight)) { echo $this->editPinad->adheight; }?>" />
                </td>
            </tr>
            <tr>
                <td style="padding-top:10px;padding-bottom:10px;" class="key hasTip" title="Enable your ad">Published</td>
                <?php  if(isset($this->editPinad->status)) {
                if($this->editPinad->status=="1")
                {
                    $y="checked";
                    $n="";
                }
                else
                {
                    $y="";
                    $n="checked";
                }
                }
                ?>
                <td>
                <table><tr>
                        <td width="10"></td>
                        <td>
                        <input type="radio" name="status" id="status" value="1" <?php if(!empty($y)) { echo $y; } ?> checked="checked" style="margin-top:0px;" />Yes</td><td><input type="radio" name="status" id="status" value="0" <?php if(!empty($n)) { echo $n; } ?> style="margin-top:0px;" />No </td>
                    </tr>
                    
                </table>
                </td>
            </tr>
            <tr>
                        <td  class="key hasTip" title="Select your position for ad">Position</td>
                        <td>
                            <select name="pin_ad_position">
                                <option>Select</option>
                                <option value="pin_top" <?php if(!empty ($this->editPinad)){ if($this->editPinad->pin_ad_position == 'pin_top') { echo 'selected=selected'; }}?>>Pin Top</option>
                                <option value="pin_bottom" <?php if(!empty ($this->editPinad)){ if($this->editPinad->pin_ad_position == 'pin_bottom') { echo 'selected=selected'; } } ?>>Pin Bottom</option>
                                <option value="home_top" <?php if(!empty ($this->editPinad)){ if($this->editPinad->pin_ad_position == 'home_top') { echo 'selected=selected'; } }?>>Home Top</option>
                                <option value="home_side" <?php if(!empty ($this->editPinad)){ if($this->editPinad->pin_ad_position == 'home_side') { echo 'selected=selected'; } }?>>Home Side</option>
                            </select>
                        </td>
                        <td><img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png" height="16px" width="16px" class="hasTip" title="For Vertical Ad select Home Side position and enter appropriate Ad Client and Ad Slot" /></td>
                    </tr>
            <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>"/>
            <input type="hidden" name="ad_id" value="<?php if(!empty($this->editPinad->ad_id)) { echo $this->editPinad->ad_id;} ?>"/>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo date("Y-m-d H:i:s");?>"/>
            <input type="hidden" name="task" value=""/>
        </form>
    </table>
</fieldset>
