import { Component, OnInit } from "@angular/core";
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { SalesDashboardService } from 'app/core/services/sales-dashboard.service';

import * as moment from 'moment';

@Component({
    selector:"sales-dashboard",
    templateUrl:"sales-dashboard.component.html",
    styleUrls:["sales-dashboard.component.scss"]
})

export class SalesDashboardComponent implements OnInit{
    currentLoginCompanyId;
    currentCompanyUser;
    isLoading : boolean = false;
    quoteList =[];
    next: '';
    startDate = moment("2018/08/01").format("YYYY-MM-DD");
    endDate;
    total_amount;
    total_number;
    filterTimeStart;
    filterTimeEnd;
    filterValue;
    currentCustomerId;
    vendorId;
    currentDate = new Date();
    currentDay = this.currentDate.getFullYear() + '-' + (this.currentDate.getMonth() + 1) + '-' + this.currentDate.getDate();
    salesType;
    currentWholeUrl;
    showFilterModal = false;
    showPresetsModal: boolean = true;
    showCustomModal: boolean = false;

    constructor(
        private SalesDashboardService : SalesDashboardService,
        private router: Router,
        private route: ActivatedRoute,
    ){ 
        this.isLoading = true;  
        this.currentLoginCompanyId = this.route.snapshot.paramMap.get('cid');
       
        if(this.currentLoginCompanyId == 0){
            this.currentLoginCompanyId = localStorage.getItem('currentLoginCompanyId');
        }
        
        console.log(this.currentLoginCompanyId);

        this.currentCustomerId = this.route.snapshot.paramMap.get('cusid');
        if (!this.currentCustomerId) {
            this.currentCustomerId = this.route.snapshot.paramMap.get('ven_id'); //if no crm customer id,  then equal to vendor id
            this.vendorId = this.route.snapshot.paramMap.get('ven_id'); //get current vendor id
        }
        this.route.queryParams.subscribe(res => {
            this.quoteList=res.data;
            console.log(res);
        })

        this.currentWholeUrl = document.URL; 
        console.log(this.currentWholeUrl);
        if(this.currentWholeUrl.includes("quote")){
            this.salesType=1;
        }else if(this.currentWholeUrl.includes("salesorder")){
            this.salesType=2;
        }else if(this.currentWholeUrl.includes("invoice")){
            this.salesType=3;
        }
        this.endDate=this.currentDay;
    }

    ngOnInit(){
        this.getNewAmountData();
    }

    getNewAmountData(){
        this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType,this.startDate, this.endDate).subscribe(
            res=>{
                this.quoteList = res.data;
                this.isLoading = false;
                this.total_amount= res.paging.quote_total_amount;
                this.total_number = res.paging.quotes_total;
                this.next = res.paging.next;
                console.log(this.quoteList);
            }
        )
    }

    // convert utc to local time
    convertUTCDateToLocalDate(date) {
        var newDate = new Date(date.getTime() + date.getTimezoneOffset() * 60);
        var offset = date.getTimezoneOffset() / 60;
        var hours = date.getHours();
        newDate.setHours(hours - offset);
        return newDate;
    }

    showFilter() {
        if (this.showFilterModal) {
            this.isLoading = true;
            this.filterValue = '';
            this.filterTimeStart = null;
            this.filterTimeEnd = null;
            this.getNewAmountData();
        }
        this.showFilterModal = !this.showFilterModal;
    }

    //close the filter div
    closeFilterModal() {
        this.showFilterModal = false;
    }

    //filter by different time range including reset range and custom range
    filterByTime(value) {
        this.isLoading = true;
        switch (value) {
            case 'today':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.currentDay, this.currentDate).subscribe(
                    res => {
                        this.filterValue = 'Today';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.currentDay;
                        this.filterTimeEnd = this.currentDate;
                    }
                );
                break;
            case 'yesterday':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.yesterdayBegin(), this.currentDay).subscribe(
                    res => {
                        this.filterValue = 'Yesterday';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.yesterdayBegin();
                        this.filterTimeEnd = this.currentDay;
                    }
                )
                break;
            case 'lastweek':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.lastweekbegin(), this.lastweekend()).subscribe(
                    res => {
                        this.filterValue = 'Last Week';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.lastweekbegin();
                        this.filterTimeEnd = this.lastweekend();
                    }
                );
                break;
            case 'lastmonth':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.lastmonthbegin(), this.lastmonthend()).subscribe(
                    res => {
                        this.filterValue = 'Last Month';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.lastmonthbegin();
                        this.filterTimeEnd = this.lastmonthend();
                    }
                );
                break;
            case 'weektodate':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.weektodate(), this.currentDate).subscribe(
                    res => {
                        this.filterValue = 'Week to Date';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.weektodate();
                        this.filterTimeEnd = this.currentDate;
                    }
                );
                break;
            case 'monthtodate':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.monthtodate(), this.currentDate).subscribe(
                    res => {
                        this.filterValue = 'Month to Date';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.monthtodate();
                        this.filterTimeEnd = this.currentDate;
                    }
                );
                break;
            case 'quartertodate':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.weektoquarter(), this.currentDate).subscribe(
                    res => {
                        this.filterValue = 'Quarter to Date';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.weektoquarter();
                        this.filterTimeEnd = this.currentDate;
                    }
                );
                break;
            case 'yeartodate':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.yeartodate(), this.currentDate).subscribe(
                    res => {
                        this.filterValue = 'Year to Date';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.yeartodate();
                        this.filterTimeEnd = this.currentDate;
                    }
                );
                break;
            case 'custom':
                this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId, this.salesType, this.customStartDate(), this.customEndDate()).subscribe(
                    res => {
                        this.filterValue = 'custom';
                        this.quoteList = res;
                        console.log(this.quoteList);
                        this.isLoading = false;
                        this.filterTimeStart = this.customStartDate();
                        this.filterTimeEnd = this.customEndDate();
                    }
                );
                break;
        }
        this.showFilterModal = false;

    }

    yesterdayBegin() {
        return moment(this.currentDate).subtract(1, 'day').format("YYYY-MM-DD");
    }

    lastweekbegin() {
        return moment(this.currentDate).subtract(7, 'days').startOf('week').format("YYYY-MM-DD");
    }
    lastweekend() {
        return moment(this.currentDate).subtract(7, 'days').endOf('week').format("YYYY-MM-DD");
    }

    lastmonthbegin() {
        return moment(this.currentDate).subtract(1, 'month').startOf('month').format("YYYY-MM-DD");
    }
    lastmonthend() {
        return moment(this.currentDate).startOf('month').format("YYYY-MM-DDTHH:mm:ss[Z]");
    }
    weektodate() {
        return moment(this.currentDate).startOf('week').format("YYYY-MM-DD");
    }
    weektoquarter() {
        return moment(this.currentDate).startOf('quarter').format("YYYY-MM-DD");
    }
    monthtodate() {
        return moment(this.currentDate).startOf('month').format("YYYY-MM-DD");
    }
    yeartodate() {
        return moment(this.currentDate).startOf('year').format("YYYY-MM-DD");
    }
    //generate preset time end

    //generate custome time
    customStartDate() {
        return moment(this.startDate).startOf('day').format("YYYY-MM-DD");
    }
    customEndDate() {
        return moment(this.endDate).add(1, 'day').endOf('day').format("YYYY-MM-DD");
    }

    //format the 
    createTime(value) {
        return moment(value).format("YYYY-MM-DD");
        // return moment(value).format("YYYY-MM-DD HH:mm:ss");
    }
    //show preset div
    showPresets() {
        this.showPresetsModal = true;
        this.showCustomModal = false;
    }
    //show custom div
    showCustom() {
        this.showPresetsModal = false;
        this.showCustomModal = true;
    }

    getTimeRange($event) {
        this.isLoading = true;
        let query = `type=${this.salesType}&starttime=${$event.startDate}&endtime=${$event.endDate}`;
        this.SalesDashboardService.getSalesentityAmount(this.currentLoginCompanyId, query).subscribe(
            res => {
                this.quoteList = res.data;
                this.isLoading = false;
                this.total_amount= res.paging.quote_total_amount;
                this.total_number = res.paging.quotes_total;
                this.startDate=$event.startDate;
                this.endDate=$event.endDate;
            }
        )
    }

    closeDateFilterModal($event) {
        // console.log($event);
        this.showFilterModal = $event;
    }
}

