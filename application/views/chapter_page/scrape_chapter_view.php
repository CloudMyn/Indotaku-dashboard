<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Scrape Comic Chapters</h1>

        <a href="<?= base_url("comic/update-comic/" . $comic["comic_slug"]) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>
    <form action="" method="post">
        <div class="row mt-3">
            <div class="col-8">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="m-0 font-weight-bold text-primary">Comic List</h5>
                    </div>
                    <div class="card-body">
                        <?= $this->session->flashdata("message") ?>
                        <div class="form-group">
                            <label for="comic-url">Comic URL</label>
                            <input type="text" class="form-control" name="comic-url" id="comic-url" placeholder="https:// or http://" value="<?= $comic["comic_url_source"] ?? set_value("comic-url") ?>">
                            <!-- <?php if (empty($total_chapters) || $total_chapters == 0 && !$is_update)  : ?>
                                <input type="text" class="form-control" name="comic-url" id="comic-url" placeholder="https:// or http://" value="<?= set_value("comic-url") ?>">
                            <?php else : ?>
                                <input type="text" class="form-control" name="comic-url" id="comic-url" placeholder="https:// or http://" value="<?= $comic["comic_url_source"] ?>">
                            <?php endif; ?> -->
                        </div>
                        <?= form_error("comic-url") ?>
                        <div class="form-group">
                            <label for="selector">Chapter Selector</label>
                            <input type="text" class="form-control" name="selector" id="selector" placeholder="CSS Chapter selector" value="<?= set_value("selector") ?>">
                        </div>
                        <?= form_error("selector") ?>
                        <div class="form-group">
                            <label for="second-selector">Image Selector</label>
                            <input type="text" class="form-control" name="second-selector" id="second-selector" placeholder="CSS Image selector" value="<?= set_value("second-selector") ?>">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">submit</button>
                    </div>
                </div>
            </div>
            <!-- Column End -->

            <div class="col-4">

                <div class="row no-gutters">
                    <div class="card shadow mb-4 col">
                        <div class="card-header">
                            <h5 class="m-0 font-weight-bold text-primary">Scrape Options</h5>
                        </div>
                        <div class="card-body py-3">
                            <div class="form-group m-0 mb-2">
                                <select name="web-saveopt" id="websaveopt" class="custom-select mr-sm-2">
                                    <option value="" selected> -- Save File Option -- </option>
                                    <option value="0"> -- Dont Save -- </option>
                                    <option value="1"> -- Save Files -- </option>
                                </select>
                            </div>
                            <div class="form-group m-0">
                                <select name="web-sources" id="webscrape" class="custom-select mr-sm-2">
                                    <option value="null"> -- Select Web Sources --</option>
                                    <?php foreach ($scrape_source as $sc) : ?>
                                        <option value="<?= $sc["ws_komik_name"] ?>"><?= $sc["ws_komik_name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?= form_error("web-saveopt") ?>
                        </div>
                    </div>
                </div>
                <!-- End Card Row -->

            </div>
            <!-- Column End -->

        </div>
    </form>

</div>


<script>
    $("#webscrape").on("change", function() {
        let val = $(this).val();
        if (!isEmpty(val)) {
            $.get(`<?= base_url("comic/ajax_get_target/") ?>${val}`, function(json, status) {
                let data = JSON.parse(json);
                $("#selector").val(data.ws_komik_selector_chap);
                $("#selector").attr("readonly", "true");
                $("#second-selector").val(data.ws_komik_selector_img);
                $("#second-selector").attr("readonly", "true");
            });
        } else {
            $("#selector").val("");
            $("#selector").removeAttr("readonly");
            $("#second-selector").val("");
            $("#second-selector").removeAttr("readonly");
        }
    });

    function isEmpty(val) {
        if (val != "null") {
            return false;
        } else {
            return true;
        }
    }
</script>