<?php header('access-control-allow-origin: *');?>
<?php
include_once('../get_galleries_new.php');
$imageIndex = $_REQUEST['image_index'];
?>

<div class='wrap' >
    <div class='onerow topRow' id="image_view">
        <div class='col12 tab col' >
            <div class="img_view_wrap_outer">
                <div id='img_view_wrap_inner' class='thumb'>
                    <?php echo count($galleries["galleries"]); if(count($galleries["galleries"]) > 0) {
                        if(count($galleries["galleries"]) > 1 && strtolower($_REQUEST['category']) != strtolower(\Config::VIDEOS_CATEGORY)){
                            echo "<select class='glry_cat_drop'>";
                            echo "<option>More Albums</option>";
                            for ($i = 0; $i < count($galleries["galleries"]); $i++) {
                                if (strtolower($galleries["galleries"][$i]['gl_cat_name']) == $_REQUEST['category'])
                                    continue;
                                ?>
                                <option class="albums" data-id="<?php echo $galleries['id']; ?>" data-catid="<?php echo $galleries['pro_cat_id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>">
                                    <?php echo $galleries["galleries"][$i]['gl_cat_name']; ?>
                                </option>
                            <?php
                            }
                            echo "</select>";
                        }

                        $more_images = array();
                        for ($i = 0; $i < count($galleries["galleries"]); $i++) {
                            if (strtolower($galleries["galleries"][$i]['gl_cat_name']) == $_REQUEST['category']) {
                                $caption = $galleries["galleries"][$i]['all_images'][$imageIndex]['caption'];
                                $currentAlbum = $galleries["galleries"][$i]['gl_cat_text'];
                                $images = $galleries["galleries"][$i]['all_images'];
                                $videos = $galleries["galleries"][$i]['all_videos'];
                                if(count($images) > $imageIndex || count($videos) > $imageIndex) {
                                    for ($j = 0; $j < count($images); $j++) {
                                        $more_images[$j]['url'] = $images[$j]['large'];
                                        $more_images[$j]['caption'] = $images[$j]['caption'];
                                        $more_images[$j]['name'] = $galleries["pro_name"];
                                        $more_images[$j]['thumb_url'] = $images[$j]['thumb'];
                                        $more_images[$j]['active'] = '';
                                        $more_images[$j]['is_video'] = 0;
                                        if ($imageIndex == $j) {
                                            $more_images[$j]['active'] = 'active';
                                        }
                                    }
                                    for ($k = $j, $l = 0; $l < count($videos); $k++, $l++) {
                                        $more_images[$k]['url'] = $videos[$l]['large'];
                                        $more_images[$k]['caption'] = $videos[$l]['caption'];
                                        $more_images[$k]['name'] = $galleries["pro_name"];
                                        $more_images[$k]['thumb_url'] = $videos[$l]['thumb'];
                                        $more_images[$k]['active'] = '';
                                        $more_images[$k]['is_video'] = 1;
                                        if ($imageIndex == $l && $j == 0) {
                                            $more_images[$k]['active'] = 'active';
                                        }
                                    }
                                    ?>
                                    <?php if (count($images) == 0) { ?>
                                        <input type='hidden' id='current_url' value='<?php echo $videos[0]['large']; ?>'/>
                                        <input type='hidden' id='current_gallery' value='<?php echo $galleries["galleries"][$i]['id'] ?>'/>
                                        <img class="main loader" src="http://img.91mobiles.com/image_gallery/gallery/images/713.GIF" style="visibility: visible;">
                                        <iframe  id='videoIframe'  scrolling="no" style="height: 100%;width: 108%;visibility: hidden;" src='<?php echo str_replace('watch?v=', 'embed/', $galleries["galleries"][$i]['all_videos'][0]['large']); ?>'
                                                 frameborder='0' allowfullscreen=''></iframe><a class='next'>›</a><a
                                            class='prev'>‹</a>
                                    <?php } else { ?>
                                        <input type='hidden' id='current_url' value='<?php echo $images[$imageIndex]['large']; ?>'/>
                                        <input type='hidden' id='current_gallery' value='<?php echo $galleries["galleries"][$i]['id'] ?>'/>
                                        <img class='main' title='<?php echo $galleries["pro_name"]; ?>'
                                             src='<?php echo $images[$imageIndex]['large']; ?>'/>
                                        <a class='next'>›</a><a class='prev'>‹</a>
                                    <?php } ?>
                                <?php
                                }
                            }
                        }
                    }else{
                        ?>
                        <input type='hidden' id='current_url' value='<?php echo $_POST['image_url']; ?>' />
                        <input type='hidden' id='current_gallery' value='<?php echo $_POST['gallery_id']; ?>' />
                        <img class='main' title='<?php echo $_POST['name']; ?>' src='<?php echo $_POST['image_url']; ?>'/><a class='next'>›</a><a class='prev'>‹</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="right_pnl">
                <div id='closeOverlay'>✕</div>
                <div class="gal_title"><?php if(isset($galleries["pro_name"])) echo $galleries["pro_name"];else echo $_POST['name']; ?></div>
                <div class="current_album"><?php if(isset($currentAlbum)) echo $currentAlbum;else echo $_REQUEST["category"]; ?></div>
                <div class="onerow thumb_nav">
                    <div class="thumb_move_right"></div>
                    <div class="thumb_move_left"></div>
                    <div class="thumbnail_row">
                        <?php
                        if(isset($more_images) && count($more_images) > 0){
                            foreach ($more_images as $url => $image) {
                                $thumb_url = !empty($image['thumb_url']) ? $image['thumb_url'] : $image['url'];
                                echo "<a  class='img_wrap_link' data-video='".$image['is_video']."' data-url='".$image['url']."' data-info='".json_encode($image)."' href='javascript:void(0);'>  <div class='img_wrap ".$image['active']."' style='background-color: #FFFFFF;background-image:url(".$thumb_url.");background-size: contain;' ></div></a>";
                            }
                        }else{
                            foreach ($_POST['more_images'] as $url => $image) {
                                $thumb_url = !empty($image['thumb_url']) ? $image['thumb_url'] : $image['url'];
                                echo "<a  class='img_wrap_link' data-url='".$image['url']."' data-info='".json_encode($image)."' href='javascript:void(0);'> <div class='img_wrap ".$image['active']."' style='background-color: #FFFFFF;background-image:url(".$thumb_url.");background-size: contain;' ></div></a>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php if(count($galleries["galleries"]) > 1){
                    ?>
                    <div class="glry_vw_mr">Albums</div>
                <?php
                } ?>
                <div class="related_section">
                    <?php if(count($galleries["galleries"]) > 0) { ?>
                        <?php for($i=0;$i<count($galleries["galleries"]);$i++) {
                            if (strtolower($galleries["galleries"][$i]['gl_cat_name']) == $_REQUEST['category']){
                                continue;
                            }
                            ?>
                            <div class="cat_album">
                                <div class="album_title"><?php echo $galleries["galleries"][$i]['gl_cat_text'];?></div>
                                <div data-id="<?php echo $galleries['id']; ?>" data-catid="<?php echo $galleries['pro_cat_id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>" class="cat_images <?php if (strtolower($galleries["galleries"][$i]['gl_cat_name']) == $_REQUEST['category']) echo 'selected_category'; ?>"
                                    >
                                    <?php if (strtolower($galleries["galleries"][$i]['gl_cat_name']) != strtolower(\Config::VIDEOS_CATEGORY) && strtolower($galleries["galleries"][$i]['gl_cat_name']) != strtolower(\Config::VIEW_360_CATEGORY) && strtolower($galleries["galleries"][$i]['gl_cat_name']) != strtolower(\Config::SLIDE_SHARE_CATEGORY)) { ?>
                                        <img data-id="<?php echo $galleries['id']; ?>" data-catid="<?php echo $galleries['pro_cat_id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>"  src="<?php echo $galleries["galleries"][$i]['all_images'][0]['small']; ?>"
                                             class="block_img">
                                    <?php
                                    }elseif(strtolower($galleries["galleries"][$i]['gl_cat_name']) == strtolower(\Config::VIEW_360_CATEGORY) || strtolower($galleries["galleries"][$i]['gl_cat_name']) == strtolower(\Config::SLIDE_SHARE_CATEGORY)){
                                        ?>
                                        <img data-id="<?php echo $galleries['id']; ?>" data-catid="<?php echo $galleries['pro_cat_id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>"  src="<?php echo $galleries["galleries"][$i]['all_videos'][0]['small']; ?>"
                                             style="margin: auto;top: 0;left: 0;right: 0;bottom: 0;position: absolute;">
                                    <?php
                                    }else{
                                        ?>
                                        <img data-id="<?php echo $galleries['id']; ?>" data-catid="<?php echo $galleries['pro_cat_id']; ?>" data-category="<?php echo strtolower($galleries["galleries"][$i]['gl_cat_name']); ?>"  src="<?php echo $galleries["galleries"][$i]['all_videos'][0]['small']; ?>"
                                             style="margin: 0 auto;width: 100%;">
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php if(count($galleries["mostpopular"]) > 0){
                        ?>
                        <div class="suggestions">---SUGGESTIONS---</div>
                        <?php for($i=0;$i<count($galleries["mostpopular"]);$i++) {
                            ?>
                            <div class="cat_album more">
                                <div style="width: 87%;;font-size: 13px;font-weight: 500;"><?php echo $galleries["mostpopular"][$i]['gl_cat_name']; ?></div>
                                <div data-catid="<?php echo $galleries["mostpopular"][$i]['pro_cat_id']; ?>" data-id="<?php echo $galleries["mostpopular"][$i]['pro_id']; ?>" data-category="design" class="cat_images" >
                                    <?php if (strtolower($galleries["mostpopular"][$i]['gl_cat_name']) != strtolower(\Config::VIDEOS_CATEGORY)) { ?>
                                        <img  data-catid="<?php echo $galleries["mostpopular"][$i]['pro_cat_id']; ?>" data-id="<?php echo $galleries["mostpopular"][$i]['pro_id']; ?>" data-category="design" src="<?php echo $galleries["mostpopular"][$i]['all_images'][0]['small']; ?>"
                                              class="block_img">
                                    <?php }else{
                                        ?>
                                        <img data-id="<?php echo $galleries['id']; ?>" data-category="<?php echo strtolower($galleries["mostpopular"][$i]['gl_cat_name']); ?>" src="<?php echo $galleries["mostpopular"][$i]['all_videos'][0]['small']; ?>" style="margin: 0 auto;height: 106px;width: 100%;">
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
        <div class="captionback" <?php if($_REQUEST['category'] == 'videos' || $more_images[0]['is_video'] == 1)echo "style='z-index:-1;'"; ?>>
            <div class="gencomment">
                <div  id="caption_line" class="photocaption" style="float: left;width: 40%;"><?php if(isset($caption)) echo $caption;else echo $_POST['caption']; ?></div>
                <div style="float: right;padding-top: 25px;">
            <span>
                <?php if(isset($galleries["pro_name"])) echo strlen($galleries["pro_name"]) > 50 ? substr($galleries["pro_name"],0,50)."..." : $galleries["pro_name"];else echo strlen($_POST['name']) > 50 ? substr($_POST['name'],0,50)."..." : $_POST['name']; ?>
            </span>
                    <?php if(isset($_REQUEST['category']) && $_REQUEST['category'] != ''){ ?>
                        <span>&nbsp; |&nbsp;&nbsp;</span>
                        <span class="album_icon"></span>
                        <span>
                &nbsp;&nbsp;<?php echo $_REQUEST['category']; ?>
            </span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
