<?php

$start = 0;

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime("@" . $datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Update Comic</h1>
    </div>


    <style>
        p.max-lines-1 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            line-clamp: 1;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    </style>

    <div class="row p-0">


        <div class="col-12">
            <div class="row">

                <div class="col-5">

                    <form action="" method="post" class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h5 class="font-weight-bold text-primary my-0">Check For Updates</h5>
                        </div>
                        <div class="card-body text-center py-1">

                            <div class="form-group row my-2 no-gutters">
                                <label for="scrape-target" class="col-sm-4 col-form-label text-left">Choose target</label>
                                <div class="col-sm-8">
                                    <select class="custom-select mr-sm-2" id="scrape-target" name="scrape-target">
                                        <option value="" selected>-- Web Target --</option>
                                        <?php foreach ($scrape_targets as $target) : ?>
                                            <?php if ($target["ws_komik_name"] !== "default") : ?>
                                                <option value="<?= $target["ws_komik_name"] ?>"><?= $target["ws_komik_name"] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?= form_error("scrape-target") ?>

                            <div class="form-group row my-2 no-gutters">
                                <label for="update-only" class="col-sm-4 col-form-label text-left">Update Only</label>
                                <div class="col-sm-8">
                                    <select class="custom-select mr-sm-2 text-center" id="update-only" name="update-only">
                                        <option value="1" selected> -- Hanya Update Saja -- </option>
                                        <option value="0"> -- Add If Not Available -- </option>
                                    </select>
                                </div>
                            </div>
                            <?= form_error("update-only") ?>

                            <div class="form-group row my-2 no-gutters">
                                <label for="save-chapter-images" class="col-sm-4 col-form-label text-left">Save Images</label>
                                <div class="col-sm-8">
                                    <select class="custom-select mr-sm-2 text-center" id="save-chapter-images" name="save-chapter-images">
                                        <option value="0" selected> -- Janga Simpan -- </option>
                                        <option value="1"> -- Simpan Saja -- </option>
                                    </select>
                                </div>
                            </div>
                            <?= form_error("save-chapter-images") ?>

                            <!-- <div class="form-group row my-2 no-gutters">
                                <label for="update-all" class="col-sm-4 col-form-label text-left">Update All Comic</label>
                                <div class="col-sm-8">
                                    <select class="custom-select mr-sm-2 text-center" id="update-all" name="update-all">
                                        <option value="0" selected> Noop </option>
                                        <option value="1"> Yap </option>
                                    </select>
                                </div>
                            </div> -->
                            <?= form_error("update-all") ?>

                        </div>
                        <div class="card-footer text-muted">
                            <button type="submit" class="btn btn-primary btn-block">updates</button>
                        </div>
                    </form>
                </div>


                <div class="col-7">
                </div>

            </div>
        </div>


        <div class="col-10 card mb-4">
            <div class="card-body py-1 pt-3">
                <div class="row mb-2 no-gutters">
                    <div class="col-md-12">
                        <?= $this->session->flashdata("message") ?>
                    </div>
                    <div class="col-md-1 text-center">
                        <h3 class="btn btn-danger btn-block"><?= $totals_result ?></h3>
                    </div>
                    <div class="col-md-1 align-center pl-2">
                        <a href="<?= base_url("comic/delete-search/comic/update") ?>" class="btn btn-warning w-75"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-md-10">
                        <form method="post">
                            <div class="input-group b">
                                <?php if (isset($keyword)) : ?>
                                    <input type="text" class="form-control bg-light border-0 small" id="comic-search" placeholder="Looking For a Comic here..." name="keyword" autocomplete="off" value="<?= $keyword ?>">
                                <?php else : ?>
                                    <input type="text" class="form-control bg-light border-0 small" id="comic-search" placeholder="Looking For a Comic here..." name="keyword" autocomplete="off">
                                <?php endif; ?>
                                <div class="input-group-append">
                                    <input class="btn btn-primary" type="submit" value="Find" name="submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped text-center table-sm" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Chapters</th>
                                <th scope="col">Last Updates</th>
                                <!-- <th scope="col">Published</th> -->
                                <th scope="col">Is Active</th>
                                <th scope="col">Check Updates</th>
                            </tr>
                        </thead>
                        <tbody id="table-body py-0">
                            <?php if (empty($comics)) : ?>
                                <tr class="">
                                    <td colspan="7" class="">
                                        <div class="alert alert-warning m-0" role="alert">
                                            <strong>No Data Was Found!</strong>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($comics as $comic) : ?>
                                <tr>
                                    <th scope="row" class="p-0 pt-2 px-3"><?= ++$start ?></th>
                                    <td class="p-0 pt-2" style="width: 30%;">
                                        <p class="m-0 max-lines-1"><a class="text-dark" href="<?= base_url("comic/update/" . $comic["comic_slug"]) ?>"><?= $comic["comic_name"] ?></a></p>
                                    </td>
                                    <td class="p-0 pt-2"><?= $comic["comic_chapters"] ?></td>
                                    <td class="p-0 pt-2"><?= time_elapsed_string($comic["comic_update"]) ?></td>
                                    <!-- <td class="p-0 pt-2"><?= time_elapsed_string($comic["comic_date"]) ?></td> -->
                                    <td class="p-0 pt-2">
                                        <?php if ($comic["comic_active"] == "1") : ?>
                                            <p class="badge badge-success">active</p>
                                        <?php else : ?>
                                            <p class="badge badge-dark">false</p>
                                        <?php endif ?>
                                    </td>
                                    <td class="p-0 pt-2">
                                        <a href="<?= base_url("comic/update/" . $comic["comic_slug"]) ?>" class="badge badge-success"><i class="fa fa-refresh" aria-hidden="true"></i> Update</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <!-- ENd Table -->
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6 ">
                        <?= $this->pagination->create_links() ?>
                    </div>
                    <div class="col-md-6 px-0">
                        <form class="form-group row p-0 m-0 no-gutters" method="POST">
                            <div class="col-2">
                                <button type="submit" name="order-btn" class="btn btn-primary btn-block w-75">Ok</button>
                            </div>
                            <div class="col-5 mr-1">
                                <select class="custom-select mr-sm-2" name="order-name">
                                    <?php foreach ($order_menu as $k => $v) : ?>
                                        <?php if ($k == $order_menu_active) : ?>
                                            <option selected value="<?= $k ?>"><?= $v ?></option>
                                        <?php else : ?>
                                            <option value="<?= $k ?>"><?= $v ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-4">
                                <select class="custom-select mr-sm-2" name="order-type">

                                    <?php foreach ($order_menu_type as $k) : ?>
                                        <?php if ($k == $order_type_active) : ?>
                                            <option selected value="<?= $k ?>"><?= $k ?></option>
                                        <?php else : ?>
                                            <option value="<?= $k ?>"><?= $k ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- end-Col -->


</div>