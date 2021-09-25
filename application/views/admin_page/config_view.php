<div class="container-fluid">

    <div class="col-md-5">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Active Server</h5>
            </div>
            <div class="card-body">
                <select class="form-control" id="web-target">
                    <?php foreach ($targets as $target) : ?>
                        <?php if ($target["ws_komik_name"] === $active_target) : ?>
                            <option value="<?= $target["ws_komik_id"] ?>" selected><?= $target["ws_komik_name"] ?></option>
                        <?php else : ?>
                            <option value="<?= $target["ws_komik_id"] ?>"><?= $target["ws_komik_name"] ?></option>
                        <?php endif ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="card-footer">
                <buttaon class="btn btn-block btn-outline-primary py-1" onclick="switch_server()">Switch</buttaon>
            </div>

        </div>
    </div>

    <div class="col-md-7">
        
    </div>


</div>

<script>
    const selected_target = document.getElementById("web-target");
    const uri = document.location.href;

    async function switch_server() {
        const params = {
            method: "GET",
            headers: {
                "Content-Type": "application/json"
            },
        };
        const result = await fetch(uri + `/${selected_target.value}`, params);
        document.location.reload();
    }
</script>