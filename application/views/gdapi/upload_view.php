<style>
    #progress-wrp {
        border: 1px solid #0099CC;
        padding: 1px;
        position: relative;
        height: 30px;
        border-radius: 3px;
        margin: 10px;
        text-align: left;
        background: #fff;
        box-shadow: inset 1px 3px 6px rgba(0, 0, 0, 0.12);
    }

    #progress-wrp .progress-bar {
        height: 100%;
        border-radius: 3px;
        background-color: #f39ac7;
        width: 0;
        box-shadow: inset 1px 1px 10px rgba(0, 0, 0, 0.11);
    }

    #progress-wrp .status {
        top: 3px;
        left: 50%;
        position: absolute;
        display: inline-block;
        color: #000000;
    }
</style>

<div class="container-fluid">
    <div>
        <input id="1" class="files" type="file" name="files[]" multiple onchange="uploadtogd(this)"/>
        <input id="3" class="files" type="file" name="files[]" multiple onchange="uploadtogd(this)"/>
        <button id="upload">Upload</button>
        <div id="progress-wrp">
            <div class="progress-bar"></div>
            <div class="status">0%</div>
        </div>
    </div>
    <div id="result">

    </div>
</div>

<script>
    $(document).ready(function() {


        const urlParams = new URLSearchParams(window.location.search);
        const code = urlParams.get('code');
        const redirect_uri = "http://localhost/komikins/Gdapi/upload" // replace with your redirect_uri;
        const client_secret = "RLUuoorq-P7tG4BbAX_u_nea"; // replace with your client secret
        const scope = "https://www.googleapis.com/auth/drive";
        var access_token = "";
        var client_id = "783987090134-62nj7vo5uh6s0pre4pcul3kq6rc6octc.apps.googleusercontent.com" // replace it with your client id;


        $.ajax({
            type: 'POST',
            url: "https://www.googleapis.com/oauth2/v4/token",
            data: {
                code: code,
                redirect_uri: redirect_uri,
                client_secret: client_secret,
                client_id: client_id,
                scope: scope,
                grant_type: "authorization_code"
            },
            dataType: "json",
            success: function(resultData) {
                localStorage.setItem("accessToken", resultData.access_token);
                localStorage.setItem("refreshToken", resultData.refreshToken);
                localStorage.setItem("expires_in", resultData.expires_in);
                window.history.pushState({}, document.title, "/komikins/gdapi/" + "upload");
            }
        });

        function stripQueryStringAndHashFromPath(url) {
            return url.split("?")[0].split("#")[0];
        }

        var Upload = function(file) {
            this.file = file;
        };

        Upload.prototype.getType = function() {
            localStorage.setItem("type", this.file.type);
            return this.file.type;
        };
        Upload.prototype.getSize = function() {
            localStorage.setItem("size", this.file.size);
            return this.file.size;
        };
        Upload.prototype.getName = function() {
            return this.file.name;
        };
        Upload.prototype.doUpload = function() {
            var that = this;
            var formData = new FormData();

            // add assoc key values, this will be posts values
            formData.append("file", this.file, this.getName());
            formData.append("upload_file", true);

            $.ajax({
                type: "POST",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer" + " " + localStorage.getItem("accessToken"));

                },
                url: "https://www.googleapis.com/upload/drive/v2/files",
                data: {
                    uploadType: "media"
                },
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress', that.progressHandling, false);
                    }
                    return myXhr;
                },
                success: function(data) {
                    $("#result").append(`<image src="https://drive.google.com/uc?id=${data.id}">`);
                    console.log("https://drive.google.com/uc?id=" + data.id);
                },
                error: function(error) {
                    console.log(error);
                },
                async: true,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                timeout: 60000
            });
        };

        Upload.prototype.progressHandling = function(event) {
            var percent = 0;
            var position = event.loaded || event.position;
            var total = event.total;
            var progress_bar_id = "#progress-wrp";
            if (event.lengthComputable) {
                percent = Math.ceil(position / total * 100);
            }
            // update progressbars classes so it fits your code
            $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
            $(progress_bar_id + " .status").text(percent + "%");
        };

        $("#upload").on("click", function(e) {
            var file = $("#1")[0].files[0];
            var upload = new Upload(file);

            // maby check size or type here with upload.getSize() and upload.getType()

            // execute upload
            upload.doUpload();
        });
    });

    function uploadtogd(gg){
        var file = $(`#${gg.id}`)[0].files[0];
        console.log(file);
        var uploads = new Upload(file);
        uploads.doUpload();
    }
</script>






















<!-- <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script> -->

<!-- add the Image Crop plugin script -->
<!-- <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script> -->


<!-- <script src="https://unpkg.com/filepond/dist/filepond.js"></script> -->
<!-- <script>
    FilePond.registerPlugin(
        // register the Image Crop plugin with FilePond
        FilePondPluginImageCrop,
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginImageTransform
    );

    const inputElement = document.querySelector('input[type="file"]');
    const pond = FilePond.create(inputElement, {
        // add the Image Crop default aspect ratio
        imageCropAspectRatio: 1,
        imageResizeTargetWidth: 256,
        imageResizeMode: 'contain',
        imageTransformVariants: {
            thumb_medium_: transforms => {
                transforms.resize.size.width = 512;

                // this will be a landscape crop
                transforms.crop.aspectRatio = .5;

                return transforms;
            },
            thumb_small_: transforms => {
                transforms.resize.size.width = 64;
                return transforms;
            }
        },
        onaddfile: (err, fileItem) => {
            console.log(err, fileItem.getMetadata('resize'));
        },
        onpreparefile: (fileItem, outputFiles) => {
            outputFiles.forEach(output => {
                const img = new Image();
                img.src = URL.createObjectURL(output.file);
                document.body.appendChild(img);
            })
        }
    });
</script>

<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script>
    const inputElement = document.querySelector('input[type="file"]');
    const pond = FilePond.create(inputElement);
</script> -->
