import { Component, OnInit } from "@angular/core";
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { SalesDashboardService } from 'app/core/services/sales-dashboard.service';


@Component({
    selector:"sales-dashboard-detail",
    templateUrl:"sales-dashboard-detail.component.html",
    styleUrls:["sales-dashboard-detail.component.scss"]
})

export class SalesDashboardDetailComponent implements OnInit{
    currentLoginCompanyId;
    isLoading : boolean = false;
    SalesDashboardDetail;
    SalesDashboardId;

    constructor(
        private SalesDashboardService : SalesDashboardService,
        private router: Router,
        private route: ActivatedRoute,
    ){ 
        this.isLoading = true;  
        this.route.params.subscribe(params=>{
            console.log(params);
            this.currentLoginCompanyId = params['cid'];
            this.SalesDashboardId = params['afqId']
        })
    }

    ngOnInit(){
        console.log(123123);
        this.isLoading = true;
        //this.getNewAmountData();

    }

    getNewAmountData(){
        this.SalesDashboardService.getNewAmountData(this.currentLoginCompanyId).subscribe(
            res=>{
                this.SalesDashboardDetail = res.data;
                this.isLoading = false;
                console.log(this.SalesDashboardDetail);
            }
        )
    }
}