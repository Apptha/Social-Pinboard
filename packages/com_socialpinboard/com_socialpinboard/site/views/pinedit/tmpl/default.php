<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component pinedit view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();

$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/public.css');
$document->addStyleSheet('components/com_socialpinboard/css/main.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');

$pinsedit = $this->editPins;
$editpinboards = $this->editPinboards;
$updatePins = $this->updatePins;
$user = JFactory::getUser();
$userId = $user->id;
if ($userId != '' && $userId != '0') {


    if (count($pinsedit) == 0) {
?>
        <div id="login_error_msg">
<?php echo JText::_('COM_SOCIALPINBOARD_UNABLE_TO_EDIT_THE_PIN'); ?>
        </div>
    <?php
    } else {
    ?>
        <div id="pin_edit">
            <form method="post" action="">
                <h3 class="edit-board-title"><?php echo JText::_('COM_SOCIALPINBOARD_EDIT_PIN'); ?></h3>

                <ul class="edit-board" style="float: left;">
                    <li>
                        <label><?php echo JText::_('COM_SOCIALPINBOARD_DESCRIPTION'); ?></label>
                        <textarea name="description_pin_edit" id="description_pin_edit" class="" ><?php if (!empty($pinsedit[0]->pin_description)) {
            echo $pinsedit[0]->pin_description;
        } ?></textarea>
                    </li>
                    <li>
                        <label><?php echo JText::_('COM_SOCIALPINBOARD_LINK'); ?></label>
                        <input class="edit-link"  type="text" name="pinlink" id="pinlink" value="<?php if (!empty($pinsedit[0]->pin_url)) {
            echo $pinsedit[0]->pin_url;
        }; ?>"/>
                    </li>
                    <li>
                        <label><?php echo JText::_('COM_SOCIALPINBOARD_BOARD'); ?></label>
                        <select name="boardname" class="custom">
                    <?php foreach ($editpinboards as $boardName) {
 ?>
                        <option value="<?php echo $boardName->board_id; ?>" <?php if (!empty($boardName->cuser_id)) { ?>
                                    class="edit-board_cont"
                            <?php } if ($pinsedit[0]->pin_board_id == $boardName->board_id) {
                            echo 'selected=selected';
                        } ?>><?php echo $boardName->board_name; ?></option>
<?php } ?>
                </select>
    <!--            <span class="down-arrow"></span>-->
            </li>
            <li>
                <label>&nbsp;</label>
                <a href="#" class="delet_pin" onclick="Modal.show('pinDiv'); return false" class="delete-board">Delete</a>
                <input type="submit"  name="SavePin" id="SavePin" value="<?php echo JText::_('COM_SOCIALPINBOARD_SAVE_PIN'); ?>" class="save-editpin"/>
            </li>
        </ul>

    </form>
    <div class="PinEditPreview" style="padding:0;">
        <a target="_blank" href="<?php if (!empty($pinsedit[0]->pin_url)) {
                        echo $pinsedit[0]->pin_url;
                    }; ?>">

            <?php
                    if ($pinsedit[0]->link_type == 'youtube' || $pinsedit[0]->link_type == 'vimeo') {
                        $src_path = $pinsedit[0]->pin_image;
                    } else {
                        $src_path = JURI::base() . "images/socialpinboard/pin_medium/" . rawurlencode($pinsedit[0]->pin_image);
                    }
            ?>
                    <div class="ImagePicker" style="width: 192px;margin-top: 0;padding: 0 10px 0 0px;">

                        <div id="PinEditPreview" style="padding: 10px;width: 182px;">
                            <img src="<?php echo $src_path; ?>" alt="pin_img" style="max-width:182px;" />
                        </div>

                    </div>
                    <div style="padding: 10px;"><?php echo $pinsedit[0]->pin_description; ?></div>
            </div>


            <div id="pinDiv" class="ModalContainer">
                <div class="modal wide delete-pin-pop">
                    <div class="header lg">
                        <a href="#" class="close" onclick="Modal.close('pinDiv'); return false;"><strong>Close</strong><span></span></a>
                        <h2><?php echo JText::_('COM_SOCIALPINBOARD_DELETE_A_PIN') ?></h2>
                    </div>
                    <p style="font-size: 18px;font-weight: bold;"><?php echo JText::_('COM_SOCIALPINBOARD_PIN_DELETE_ALERT'); ?></p>
                    <form action="" method="post">
                        <input type="submit" name="delete_pin" id="delete_pin" value="<?php echo JText::_('COM_SOCIALPINBOARD_DELETE_PIN'); ?>"/>
                        <input type="button" onclick="Modal.close('pinDiv'); return false" name="no_delete" id="no_delete" value="<?php echo JText::_('COM_SOCIALPINBOARD_CANCEL'); ?>"/>
                    </form>
                </div>
            </div>
            <style type="text/css">.ImagePicker .price.visible {left: -35px;}</style>
        </div>
<?php
                }
            } else {
                $redirectUrl = JRoute::_('index.php?option=com_socialpinboard&view=people', false);
                $app = JApplication::getInstance();
                $app->redirect($redirectUrl);
            }
?>
<script type="text/javascript">

    var pinID = "118";

    scr(document).ready(function(){
        EditPin.setup();
    });

</script>