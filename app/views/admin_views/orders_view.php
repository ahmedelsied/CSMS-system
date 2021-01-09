<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-9 col-xs-12 text-center">
                            <label>من تاريخ:</label>
                            
                            <input type="date" class="start-date custom-date"/>
                            <br class="visible-xs">
                            <label>إلى تاريخ:</label>
                            <input type="date" class="end-date custom-date"/>
                            <br class="visible-xs">
                            <input type="submit" value="تنفيذ" class="btn btn-danger any-date" id="get-data"/>
                        </div>
                        <br class="visible-xs">
                        <br class="visible-xs">
                        <br class="visible-xs">
                        <br class="visible-xs">
                        <hr class="visible-xs">
                        <div class="col-md-3 col-xs-12">
                            <p class="date-card active-date-card" data-date="curr_month" id="date-target" data-url="orders">الشهر الحالي</p>
                            <p class="date-card" data-date="all_time">كل الوقت</p>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <br>
                    <div id="curve_chart" style="height: 500px"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
