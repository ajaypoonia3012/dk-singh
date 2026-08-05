import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";

window.createMediaCropper = (image, options = {}) => {

    return new Cropper(image, {

        viewMode: 1,

        dragMode: "move",

        autoCropArea: 1,

        responsive: true,

        background: false,

        movable: true,

        zoomable: true,

        rotatable: true,

        scalable: true,

        checkOrientation: true,

        ...options,

    });

};
