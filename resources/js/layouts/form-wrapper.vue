<template>
  <div class="">
    <div class="card fieldset_form">
      <div class="card-body" style="padding: 3px">
        <h5 class="card-title mb-0">
          <slot name="form-title"></slot>
        </h5>
      </div>
      <div class="form-group-input" v-show="isErrorForm">
        <el-alert :title="titleMessage" type="warning" effect="dark">
        </el-alert>
      </div>

      <div class="container-fluid">
        <div class="row">
          <slot></slot>
        </div>
      </div>
      <!-- <div class="form-group-button">
        <slot name="form-buttons"></slot>
      </div> -->
    </div>
  </div>
</template>
<script>
export default {
  name: "form-wrapper",
  props: ["isErrorForm", "titleMessage"],
};
</script>
<style lang='scss' scoped>
.fieldset_form {
  position: relative;
  .card-title {
    display: inline-block;
    position: absolute;
    top: -7px;
    left: 50px;
    background: #fff;
    padding: 5px;
  }
  .container-fluid {
    padding: 0 15px;
    & > .row {
      border: 1px solid grey;
      padding-top: 30px;
    }
  }
}
</style>