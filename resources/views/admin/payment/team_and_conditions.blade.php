<p class="blue">
<strong><em><u>Team And Condition</u>:</em></strong><br>
<!-- <small> -->
Any information passed through the customer's browser can potentially be modified by the customer, or even by third parties to fraudulently alter the transaction data. Therefore all transactional information should not be passed through the browser in a way that could potentially be modified (e.g. hidden form fields). Transaction data should only be accepted once from a browser at the point of input, and then kept in a way that does not allow others to modify it (e.g. database, server session, etc.). Any transaction information displayed to a customer, such as amount, should be passed only as display information and the actual transactional data should be retrieved from the secure source at the point of processing the transaction.
<br>
Fields like return links back to the order page (AgainLink), titles, and any other non-transactional information are only included here in the example for information purposes. They do not apply to the transaction and do not have be included in production code orders.
<!-- </small> -->
</p>