import {errorMessage} from '../errors/error-code'
export default function(e) {
    this.$notify({
        title: "Thông báo",
        message: errorMessage[e.response.status],
        type: "warning",
      });
}