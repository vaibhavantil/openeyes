<div class="dashboard-container" id="tour2">
  <?php foreach ($items as $box_number => $item) {?>

    <?php
    $container_id = isset($item['options']['container-id']) ? $item['options']['container-id'] : "js-toggle-container-$box_number";
    $is_open = isset($item['options']['js-toggle-open']) && $item['options']['js-toggle-open'];
    ?>

    <section id="<?php echo $container_id; ?>" class="box dashboard js-toggle-container">
      <h3 class="box-title"><?= $item['title'] ?></h3>
      <?php if ($sortable) { ?><span class="sortable-anchor fa fa-arrows"></span><?php }?>
      <a href="#" class="toggle-trigger <?php echo  $is_open ? 'toggle-hide' : 'toggle-show' ?> js-toggle">
        <span class="icon-showhide">
          Show/hide this section
        </span>
      </a>
      <div class="js-toggle-body" style="<?php echo  $is_open ? 'display:block' : 'display:none' ?>">
        <?= $item['content']; ?>
      </div>
    </section>
  <?php } ?>
</div>
<?php if ($sortable) { ?>
  <script type="text/javascript">
  $(document).ready(function() {
    $('.dashboard-container').sortable({handle: '.sortable-anchor'});
  });
  </script>

<?php }?>
<?php
$new_feature_help_parameters = array(
  'splash_screen' => array(
    array(
      'element' => '.large-6.medium-7.column',
      'title' => 'User Panel',
      'content' => 'This is where...',
      'contentLess' => 'This is....',
      'backdropContainer' => 'header'
    ),
    array(
      'element' => '.oe-find-patient:first',
      'title' => 'Paitent Search',
      'content' => 'This is where...',
      'contentLess' => 'This is....',
      'showParent' => 'true'
    )
  ),
  'tours' => array(
    'tour1' => array(
      array(
       'element' => '.large-6.medium-7.column',
       'title' => 'User Panel',
       'content' => 'This is where...',
       'contentLess' => 'This is....',
       'backdropContainer' => 'header'
     ),
     array(
       'element' => '.oe-find-patient:first',
       'title' => 'Paitent Search',
       'content' => 'This is where...',
       'contentLess' => 'This is....',
       'showParent' => 'true'
     )
   ),
   'tour2' => array(
     array(
      'element' => '.large-6.medium-7.column',
      'title' => 'User Panel',
      'content' => 'This is where...',
      'contentLess' => 'This is....',
      'backdropContainer' => 'header'
    ),
    array(
      'element' => '.oe-find-patient:first',
      'title' => 'Paitent Search',
      'content' => 'This is where...',
      'contentLess' => 'This is....',
      'showParent' => 'true'
    )
   )
 ),
  'download_links' => array(
    'pdf1' => 'http://www.axmag.com/download/pdfurl-guide.pdf',
    'pdf2' => 'http://www.axmag.com/download/pdfurl-guide.pdf'
  )
);




$feature_steps =  array(
             array(
                   'element' => '.large-6.medium-7.column',
                   'title' => 'User Panel',
                   'content' => 'This is where...',
                   'contentLess' => 'This is....',
                   'backdropContainer' => 'header'
                  ),
             array(
                   'element' => '.oe-find-patient:first',
                   'title' => 'Paitent Search',
                   'content' => 'This is where...',
                   'contentLess' => 'This is....',
                   'showParent' => 'true'
                  ),
            array(
                  'element' => '#1-inbox-container',
                  'title' => 'Messages',
                  'content' => 'This is where...',
                  'contentLess' => 'This is....',
                  'placement' => 'bottom',
                 )
);

//$this->widget('application.widgets.FeatureHelp', array('steps' => $feature_steps));
$this->widget('application.widgets.NewFeatureHelp', $new_feature_help_parameters);
?>
