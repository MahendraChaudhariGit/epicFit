

<style type="text/css">
    .hide {
        display: none;
    }

.remove-img-cross {
    top: 73px;
}
.image-uploader{
    display: none;
}

    /* #add-post-image {
        position: absolute;
        top: 165px;
        right: 753px;
        z-index: 100;
     }*/

</style>


   <div id="post" class="tab-pane">
    <form id="form-new-post" method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="joms-stream__body">
            <div class="joms-tab__content joms-tab__content--stream  app-core">
                <div class="joms-gap"></div>
                <textarea name="content" placeholder="What's on your mind ?" id="content"></textarea>
                <p id="new-post" style="color: red;" class="hidden"> This post appears to be blank. Please write
                    something or attach a link or photo to post.</p>
                <div class="joms-gap"></div>
                {{-- <div class="col-md-12 text-center bg-ornge p-0"> --}}
                <div class="text-center bg-ornge p-0">
                    <button type="button" class="close remove-img remove-img-cross hide" id="add-post-image"
                        onclick="removePostImage()">×</button>
                    <button type="button" class="close remove-img remove-img-cross hide" id="remove-post-video"
                        onclick="removePostVideo()">×</button>
                    {{-- <img id="post_image" src="" /> --}}
                    <span class="more5img"></span>
                    <div class="input-images"></div>
                    {{-- <div id="post_image"> </div> --}}
                    <video width="100%" id="preview-post-video" controls="" playsinline="" src="" class="hidden">
                    </video>
                    <div class="hidden delete-image-value" >
                    </div>
                    {{-- <input hidden name="deleteImages" value=""/> --}}
                </div>
                <div class="form-group post-section row">
                    <div class="col-md-12 col-xs-12">
                        <input type="file" accept="image/*" class="image-input" name="images[]" id="upload_image"
                            multiple />
                        <button type="button" class="btn btn-default btn-add-image btn-sm" onclick="uploadPostImage()">
                            <i class="fa fa-image"></i> Add Image
                        </button>
                        <input type="file" accept="video/*" class="video-input" name="video" id="upload_video" />
                        <button type="button" class="btn btn-default btn-add-video btn-sm" onclick="uploadPostVideo()">
                            <i class="fa fa-video"></i> Add Video
                        </button>
                        <button class="joms-focus__button--add submit-post-btn" type="submit"
                            id="submit_post">Post</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

