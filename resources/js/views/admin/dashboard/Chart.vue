<script>
import { Line } from "vue-chartjs";

export default {
    extends: Line,
    methods:{
        loadChart(data,options) {
            this.renderChart(data, options);
        }   
    }
};
</script>
