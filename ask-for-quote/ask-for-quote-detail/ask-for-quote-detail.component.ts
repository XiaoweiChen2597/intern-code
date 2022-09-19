import { Component, OnInit } from "@angular/core";
import { Router, ActivatedRoute, ParamMap } from '@angular/router';
import { AskforQuoteService } from 'app/core/services/askfor-quote.service';
import { FormControl } from '@angular/forms';




@Component({
    selector:"ask-for-quote-detail",
    templateUrl:"ask-for-quote-detail.component.html",
    styleUrls:["ask-for-quote-detail.component.scss"]
})

export class AskforQuoteDetailComponent implements OnInit{

    company_id;
    askForQuoteId;
    isLoading : boolean = false;
    askForQuoteDetail ;
    PriceControl = new FormControl('');
    constructor(
        private route: ActivatedRoute,
        private askForQuoteService : AskforQuoteService
        
    ){
        this.route.params.subscribe(params=>{
            console.log(params);
            this.company_id = params['cid'];
            this.askForQuoteId = params['afqId']
        })
        console.log('this is askforquote details');
    }

    ngOnInit(){
        console.log(123123);
        this.isLoading = true;
        this.browse();
    }

    browse(){
        this.askForQuoteService.read(this.company_id, this.askForQuoteId).subscribe(
            res=>{
                this.askForQuoteDetail = res;
                this.isLoading = false;
                console.log(res);
            }
        )
    }

    update(){
        console.log(this.askForQuoteDetail);
        this.askForQuoteDetail.is_complete=1;
        this.askForQuoteService.update(this.company_id, this.askForQuoteId,this.askForQuoteDetail).subscribe(
            res=>{
                this.askForQuoteDetail = res;
                console.log(res);
        })
                
    }
}