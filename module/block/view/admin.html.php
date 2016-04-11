<?php
/**
 * The admin view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<table class='table table-form'>
  <tr><th class='w-100px'></th><td></td></tr>
  <?php if(!empty($modules)):?>
  <tr>
    <th class='w-100px'><?php echo $lang->block->lblModule; ?></th>
    <?php
    $moduleID = '';
    if($block) $moduleID = $block->source != '' ? $block->source : $block->block;
    ?>
    <td><?php echo html::select('modules', $modules, $moduleID, "class='form-control'")?></td>
  </tr>
  <?php endif;?>
  <tr><?php if(!empty($blocks)) echo $blocks;?></tr>
</table>
<div id='blockParam'><?php if(!empty($blocks)) echo $blocks;?></div>
<?php js::set('index', $index)?>
<?php include '../../common/view/footer.lite.html.php';?>