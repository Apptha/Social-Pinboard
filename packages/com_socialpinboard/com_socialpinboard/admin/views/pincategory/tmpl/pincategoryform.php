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
            
         if(task=='apply' || task=='save'|| document.formvalidator.isValid(document.id('adminForm'))){
           
                         var cat_name = document.getElementById('category_name').value;                        
             if(cat_name!='')
                 {
                     Joomla.submitform(task, document.getElementById('adminForm'));
                 }
                 else if(cat_name=='')
                     {
                         alert('Please enter a category name');
                     }
                     }
        
       
    }
</script>
<fieldset class="adminform">
    <legend>Categories</legend>
    <table class="admintable">
        <form action="index.php?option=com_socialpinboard&layout=pincategory" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data">
            <tr>
                <td class="key hasTip" title="Enter your category name">Category Name:</td>
                <td>
                    <table><tr>
                            <td width="10"></td>
                            <td>
                                <input type="text" name="category_name" class="input required" id="category_name" value="<?php if(isset($this->editPincategory->category_name)) { echo $this->editPincategory->category_name;} ?>" onblur="//check_name();">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td  style="padding-top:10px;padding-bottom:10px;" class="key hasTip" title="Enable your category">Published</td>
                <?php  if(isset($this->editPincategory->status)) {
                if($this->editPincategory->status=="1")
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
                        <input type="radio" name="status" id="status" value="1" <?php if(!empty($y)) { echo $y; } ?> checked="checked" style="margin-top:0px;" />Yes</td>
                        <td><input type="radio" name="status" id="status" value="0" <?php if(!empty($n)) { echo $n; } ?> style="margin-top:0px;" />No </td>
                    </tr>
					
					
                </table>
				</td>
            </tr>
				
				    <tr>
                          <td class="key hasTip" title="Upload your category image">Category Image:   </td>
                            <td>
				<input type="file" name="category_image" id="category_image"  />
                            </td>
                        </tr>
                    

            <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>"/>
            <input type="hidden" name="category_id" value="<?php if(!empty($this->editPincategory->category_id)) { echo $this->editPincategory->category_id;} ?>"/>
            <input type="hidden" name="created_date" id="created_date" value="<?php echo date("Y-m-d H:i:s");?>"/>
            <input type="hidden" name="category_image_name" id="category_image_name" value="<?php if(!empty($this->editPincategory->category_image)) { echo $this->editPincategory->category_image;} ?>"/>
            <input type="hidden" name="task" value="<?php echo JRequest::getVar('task');?>"/>
            <input type="hidden" name="cid" value="<?php echo JRequest::getVar('cid');?>"/>
        </form>
    </table>
</fieldset>
