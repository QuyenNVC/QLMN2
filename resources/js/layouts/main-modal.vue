<template>
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div
      class="modal-dialog"
      :width="widthModal"
      :style="`max-width: ${widthModal}`"
    >
      <div class="modal-content">
        <button
          type="button"
          class="btn-close position-absolute"
          data-bs-dismiss="modal"
          aria-label="Close"
          style="
            top: 7px;
            right: 7px;
            z-index: 1;
            outline: non !important;
            box-shadow: none !important;
          "
        ></button>
        <div class="modal-body" style="padding: 0 40px; padding-top: 15px">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "main-modal",
  props: ["widthModal"],
};
</script>