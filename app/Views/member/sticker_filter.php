<?php global $lang;?>
<style>
    .sticker-form-container {
    width: 400px;              /* control width */
    margin: 60px auto;         /* center horizontally + top spacing */
    padding: 25px;
    border: 1px solid #ddd;    /* optional */
    border-radius: 8px;        /* optional */
    background: #fff;          /* optional */
}
.sticker-heading {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
}
</style>
<div class="sticker-form-container">
<h4 class="sticker-heading">Sticker Filter</h4>
<form method="post" action="<?= base_url('member/print_stickers') ?>" target="_blank">
    
    <div class="form-group">
        <label>Membership Status</label>
        <select name="membership_status" class="form-control">
            <option value="">All</option>
            <option value="Active">Active</option>
            <option value="Deceased">Deceased</option>
        </select>
    </div>

    <div class="form-group">
        <label>Country</label>
        <select name="country" class="form-control">
            <option value="">All</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Australia">Australia</option>
            <option value="United Kingdom; England">United Kingdom; England</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Canada">Canada</option>
            <option value="Others">Other than Malaysia</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Print Stickers</button>
    </form>
    </div>

</form>