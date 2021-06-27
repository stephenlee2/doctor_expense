<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('change_type');
            $table->string('covered_recipient_type');
            $table->string('teaching_hospital_ccn');
            $table->string('teaching_hospital_id');
            $table->string('teaching_hospital_name');
            $table->string('physician_profile_id');
            $table->string('physician_first_name');
            $table->string('physician_middle_name');
            $table->string('physician_last_name');
            $table->string('physician_name_suffix');
            $table->string('recipient_primary_business_street_address_line1');
            $table->string('recipient_primary_business_street_address_line2');
            $table->string('recipient_city');
            $table->string('recipient_state');
            $table->string('recipient_zip_code');
            $table->string('recipient_country');
            $table->string('recipient_province');
            $table->string('recipient_postal_code');
            $table->string('physician_primary_type');
            $table->string('physician_specialty');
            $table->string('physician_license_state_code1');
            $table->string('physician_license_state_code2');
            $table->string('physician_license_state_code3');
            $table->string('physician_license_state_code4');
            $table->string('physician_license_state_code5');
            $table->string('submitting_applicable_manufacturer_or_applicable_gpo_name');
            $table->string('applicable_manufacturer_or_applicable_gpo_making_payment_id');
            $table->string('applicable_manufacturer_or_applicable_gpo_making_payment_name');
            $table->string('applicable_manufacturer_or_applicable_gpo_making_payment_state');
            $table->string('applicable_manufacturer_or_applicable_gpo_making_payment_country');
            $table->double('total_amount_of_payment_usdollars', 8, 2);
            $table->timestamp('date_of_payment');
            $table->string('number_of_payments_included_in_total_amount');
            $table->string('form_of_payment_or_transfer_of_value');
            $table->string('nature_of_payment_or_transfer_of_value');
            $table->string('city_of_travel');
            $table->string('state_of_travel');
            $table->string('country_of_travel');
            $table->string('physician_ownership_indicator');
            $table->string('third_party_payment_recipient_indicator');
            $table->string('name_of_third_party_entity_receiving_payment_or_transfer');
            $table->string('charity_indicator');
            $table->string('third_party_equals_covered_recipient_indicator');
            $table->string('contextual_information');
            $table->string('delay_in_publication_indicator');
            $table->string('record_id');
            $table->string('dispute_status_for_publication');
            $table->string('product_indicator');
            $table->string('name_of_associated_covered_drug_or_biological1');
            $table->string('name_of_associated_covered_drug_or_biological2');
            $table->string('name_of_associated_covered_drug_or_biological3');
            $table->string('name_of_associated_covered_drug_or_biological4');
            $table->string('name_of_associated_covered_drug_or_biological5');
            $table->string('ndc_of_associated_covered_drug_or_biological1');
            $table->string('ndc_of_associated_covered_drug_or_biological2');
            $table->string('ndc_of_associated_covered_drug_or_biological3');
            $table->string('ndc_of_associated_covered_drug_or_biological4');
            $table->string('ndc_of_associated_covered_drug_or_biological5');
            $table->string('name_of_associated_covered_device_or_medical_supply1');
            $table->string('name_of_associated_covered_device_or_medical_supply2');
            $table->string('name_of_associated_covered_device_or_medical_supply3');
            $table->string('name_of_associated_covered_device_or_medical_supply4');
            $table->string('name_of_associated_covered_device_or_medical_supply5');
            $table->integer('program_year');
            $table->timestamp('payment_publication_date');


            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
