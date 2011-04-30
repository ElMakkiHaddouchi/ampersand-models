--[Ordering]------------------------------------------------
PATTERN "Ordering"
PURPOSE PATTERN Ordering IN ENGLISH
{+This pattern provides the language needed for clients to convey to a webshop what it is they wish to be delivered. Also, it provides guarantees to limit needless work.-}

PURPOSE RELATION isPlacedBy IN ENGLISH
{+For shipping ordered products, sending a corresponding invoice etc., providers must know (details about) the client who placed that order. For the sake of simplicity, we have left out name, address, zipcode, etcetera.-}
isPlacedBy :: Order * Client [UNI] PRAGMA "" " has been placed by "
  = [ ("Order45666", "Applegate")
    ; ("Order22096", "Brown"    )
    ; ("Order45683", "Conway"   )
    ; ("Order13245", "Deirdre"  )
    ].

PURPOSE RELATION deliveryAddress IN ENGLISH
{+Commitment to an order should only take place if it is known where the delivery is to be shipped to. Hence, a delivery address should be part of an order-}
deliveryAddress :: Order * Address [UNI] PRAGMA "The delivery for " " must be shipped to "
  = [ ("Order45666", "Appleroad 3, Appleby")
    ; ("Order22096", "Brownstreet 4, Brisbane")
    ; ("Order45683", "Conway 5, Carlisle")
    ; ("Order13245", "Droolsway 6, Davenport")
   ].

PURPOSE RELATION orderedItem[Order*Product]
{+For shipping ordered products and sending a corresponding invoice, the list of products to be shipped and billed must be known. Each ordered item corresponds to a line on the order form.
For the sake of simplicity, we have left out quantity, product codes, etcetera.-}
orderedItem :: Order * Product PRAGMA "" " mentions " " as an order item"
  = [ ("Order45666", "Jelly beans #4921")
    ; ("Order45666", "Coleslaw #3714")
    ; ("Order22096", "Cookies #0382")
    ; ("Order45683", "Peanut butter #1993")
    ; ("Order13245", "Kookaburra")
    ].

PURPOSE RELATION cleanOrder IN ENGLISH
{+Before it can be decided whether or not to accept an order, all information must be available (a) to make this decision and (b) to handle the order if it were accepted. An order that has the property 'cleanOrder' satisfies this condition.-}
cleanOrder :: Order * Order PRAGMA "" " satisfies the 'cleanOrder' conditions"
  = [ ("Order45666", "Order45666")
    ; ("Order22096", "Order22096")
    ; ("Order45683", "Order45683")
    ; ("Order13245", "Order13245")
   ].
PURPOSE RULE "clean order" IN ENGLISH
{+In order to decide whether or not to accept an order, all information must be available (a) to make this decision and (b) to handle the order if it were accepted.
This is the case when:

* at least one product is ordered;
* the address to which the delivery is to be sent is known;
* the address to which the invoice is to be sent is known.
-}
RULE "clean order": cleanOrder = I /\ (orderedItem; V /\ deliveryAddress; V /\ isPlacedBy; invoiceAddress; V)
PHRASE "Every clean order orders at least one product; also, the delivery- and invoice addresses are known."

PURPOSE RELATION acceptedBy IN ENGLISH
{+Whether or not an order is to be carried out is a decision by the order processor. The consequence of accepting an order will be that all products ordered by a client will be assembled into a delivery that is subsequently shipped to the client. 

Because of such consequences, it must be known whether or not an order is accepted. In order to put responsibility of handling an order into one hand, we need to know which order processor accepted the order.-}
acceptedBy :: Order * Processor [UNI] PRAGMA "" "has been accepted by"
  = [ ("Order45666", "Carter")
    ; ("Order22096", "Candy")
    ; ("Order45683", "Carter")
    ].

PURPOSE RELATION rejectedBy IN ENGLISH
{+Whether or not an order is to be carried out is a decision by the order processor. The consequence of rejecting an order is that the order will not be processed further and the client will not receive any products it may have ordered. An order processor may reject orders for a number of reasons, e.g. when the product is not carried, or when the amount payable would be so large that the risk of not receiving payment is too big. 

Because of such consequences, it must be known whether or not an order is rejected. In order to put responsibility of handling an order into one hand, we need to know which order processor rejected the order.-}
rejectedBy :: Order * Processor [UNI] PRAGMA "" "has been rejected by"
  = [ ("Order13245", "Grinder")
    ].

PURPOSE RULE "accepting orders" IN ENGLISH
{+Orders can only be accepted if they are clean, and have not been rejected.-}
RULE "accepting orders":  acceptedBy |- cleanOrder;(acceptedBy /\ -rejectedBy)
PHRASE IN ENGLISH "Orders that are accepted must have the property 'cleanOrder', and may not have been rejected."

PURPOSE RULE "rejecting orders" IN ENGLISH
{+Orders can only be rejected if they are clean, and have not been accepted.-}
RULE "rejecting orders":  rejectedBy |- cleanOrder;(rejectedBy /\ -acceptedBy)
PHRASE IN ENGLISH "Orders that are rejected must have the property 'cleanOrder', and may not have been accepted."

PURPOSE RELATION orderProcessor IN ENGLISH
{+In order to improve the quality of order handling, a single order processor will be responsible for all activities pertaining to the handling of an order.-}
orderProcessor :: Order -> Processor PRAGMA "" " is to be handled by "
 = [ ("Order45666", "Carter")
   ; ("Order22096", "Candy")
   ; ("Order45683", "Carter")
   ; ("Order13245", "Grinder")
  ].


PURPOSE RULE "assigning the order processor" IN ENGLISH
{+The responsibility for handling an order must be assigned such that it is unambiguous. One way of doing this is to designate the order processor that decides acceptance or rejection of an order as the handler for that order.-}
RULE "assigning the order processor": acceptedBy \/ rejectedBy  |- orderProcessor
PHRASE IN ENGLISH "The order processor that decides on acceptance or rejection of an order, is the designated handler for that order."

ENDPATTERN
--[Order Picking]-------------------------------------------
PATTERN "Order Picking"
PURPOSE PATTERN "Order Picking" IN ENGLISH
{+This pattern ensures that after an order has been accepted, 
a delivery (package) is created that consists of all ordered items (and no more than that), 
so that it can be sent to the customer.-}

PURPOSE RELATION correspondsTo
{+In order for orders to be delivered to a client,
the ordered items must be picked and assembled into a delivery. 
In order to assemble a delivery, it must be known to which order it corresponds-}
correspondsTo :: Delivery -> Order PRAGMA "" " corresponds to "
 = [ ("D667pw", "Order45666")
   ; ("D22xyz", "Order22096")
   ; ("D83js7", "Order45683")
   ].

PURPOSE RELATION containsPickedItem IN ENGLISH
{+In order to be able to tell whether or not a delivery is ready for shipment, 
it is necessary to know of which products a delivery consists.
Each delivery item corresponds to a line on the delivery form.
For the sake of simplicity, we have left out quantity, product codes, etcetera.-}
containsPickedItem :: Delivery * Product PRAGMA "" " contains "
  = [ ("D667pw", "Jelly beans #4921")
    ; ("D667pw", "Coleslaw #3714")
    ; ("D22xyz", "Cookies #0382")
    ; ("D83js7", "Peanut butter #1993")
    ].

PURPOSE RELATION readyForShipping IN ENGLISH
{+It is necessary to distinguish deliveries that contain all items mentioned in the corresponding orders from those that do not. A delivery is said to have the property 'readyForShipping' if this is the case.-}
readyForShipping :: Delivery * Delivery [PROP] PRAGMA "" " is ready for shipping, meaning that " " contains all items that are mentioned on the corresponding order"
 = [ ("D667pw", "D667pw")
   ; ("D22xyz", "D22xyz")
   ; ("D83js7", "D83js7")
   ].

RULE "ready for shipping":
-- readyForShipping = (containsPickedItem <> (correspondsTo;orderedItem)~)
readyForShipping = (containsPickedItem ! -(correspondsTo;orderedItem)~) /\ (-containsPickedItem ! (correspondsTo;orderedItem)~)
PHRASE IN ENGLISH "A delivery is ready for shipping iff each item in that delivery is mentioned on the corresponding order."

PURPOSE RELATION hasBeenShippedBy IN ENGLISH
{+In order to ensure that all handling of a single order is put in one hand, it is necessary to know who shipped a delivery.-}
hasBeenShippedBy :: Delivery * Processor [UNI] PRAGMA "" " has been shipped by "
 = [ ("D667pw", "Carter")
   ; ("D22xyz", "Candy")
   ; ("D83js7", "Carter")
   ].

PURPOSE RULE "shipping" IN ENGLISH
{+Deliveries should only be shipped if they are ready for shipping. Shipping of a delivery may only be done by the order handler that accepted the corresponding order, so as to ensure that responsibilities remain in one hand.-}
RULE "shipping":    hasBeenShippedBy  |- readyForShipping; correspondsTo; acceptedBy
PHRASE IN ENGLISH "Deliveries may only be shipped (1) by the person that accepted the corresponding order and (2) if the delivery is ready for shipping."

PURPOSE RELATION hasBeenShippedBy IN ENGLISH
{+In order to ensure that all deliveries are shipped to the client that ordered them, it is necessary to know to which client a delivery is shipped.-}
hasBeenShippedTo :: Delivery -> Client PRAGMA "" " has been shipped to "
 = [ ("D667pw",	"Applegate")
   ; ("D22xyz",	"Brown")
   ; ("D83js7",	"Conway")
   ].
ENDPATTERN
--[Billing]------------------------------------------------
PATTERN "Billing"
PURPOSE PATTERN "Billing" IN ENGLISH
{+In order to do business (make a profit) it is necessary to bill clients for the deliveries that are shipped to their orders. This pattern ensures that the correct bills are sent and payment is received.-}

PURPOSE RELATION invoiceAddress IN ENGLISH
{+In order to send invoices (as well as other client-related communications), an address is needed to send such messages to.-}
invoiceAddress :: Client * Address [UNI] PRAGMA "All invoices that " " should pay, must be sent to"
  = [ ("Applegate", "Appleroad 3, Appleby")
    ; ("Brown", "Brownstreet 4, Brisbane")
    ; ("Conway", "Conway 5, Carlisle")
    ; ("Deirdre", "Droolsway 6, Davenport")
   ].

PURPOSE RELATION hasBeenSentTo IN ENGLISH
{+In order to receive payment for a delivery, the client that ordered this delivery must be informed about the cost. To this end, it must be possible to send invoices to a client's (invoice)address.-}
hasBeenSentTo :: Invoice -> Client PRAGMA "" " was sent to "
 = [ ("I667pw",	"Applegate")
   ; ("I22xyz",	"Brown")
   ; ("I83js7",	"Conway")
   ].

PURPOSE RULE "invoice address is available" IN ENGLISH
{+In order to send an invoice to a client, the invoice address must be known.-}
RULE "invoice address is available": hasBeenSentTo |- hasBeenSentTo; (I /\ invoiceAddress; invoiceAddress~)
PHRASE "An invoice may only be sent to a client if the clients invoice address is known."

PURPOSE RELATION covers IN ENGLISH
{+In order to make sure that every delivery will ultimately be paid for, and also in order to convince a client to pay for a delivery, it is necessary to link a delivery with an invoice.-}
covers :: Invoice -> Delivery [INJ] PRAGMA "" " covers "
 = [ ("I667pw",	"D667pw")
   ; ("I22xyz",	"D22xyz")
   ; ("I83js7",	"D83js7")
   ].

PURPOSE RELATION hasBeenSentBy IN ENGLISH
{+In order to ensure that the responsibility for handling an order remains in one hand, it must be known which order processor has sent which invoices.-}
hasBeenSentBy :: Invoice -> Processor PRAGMA "" " has been sent by "
 = [ ("I667pw",	"Carter")
   ; ("I22xyz",	"Candy")
   ; ("I83js7",	"Carter")
   ].

PURPOSE RULE "sending of invoices" IN ENGLISH
{+In order to make order processors accountable for the orders they process, we must ensure that the the order processor that handles an order also sends the corresponding invoice.-}
RULE "sending of invoices": hasBeenSentBy |- covers; correspondsTo; acceptedBy
PHRASE "An invoice that covers the delivery corresponding to an order shall be sent by the order processor that accepted this order."

PURPOSE RELATION hasPaid IN ENGLISH
{+In order to establish that a client has paid for a delivery, it must be possible to know whether or not payment has been received for a particular invoice.-}
hasPaid :: Client * Invoice [INJ] PRAGMA "Payment by " " has been received for "
 = [ ("Applegate", "I667pw")
   ; ("Brown", "I22xyz")
   ; ("Conway", "I83js7")
   ].

PURPOSE RULE "sending invoices to clients" IN ENGLISH
{+In order to prevent conflicts about erroneously sent invoices, it must be ensured that they are sent to the appropriate clients.-}
RULE "sending invoices to clients": hasBeenSentTo |- covers; correspondsTo; isPlacedBy
PHRASE IN ENGLISH "An invoice that covers a delivery may only be sent to (the invoice address) of the client that has ordered the delivery."

PURPOSE RULE "correct payments" IN ENGLISH
{+To prevent conflicts about wrong payments (and to uphold the brand of the webshop), payments by clients shall only be accepted for invoices that correspond to orders placed by such clients.
-}
RULE "correct payments": hasPaid |- hasBeenSentTo~
PHRASE IN ENGLISH "Payments by a client shall only be received if they are made for an invoice that has been sent to that client."
 
ENDPATTERN
--[Sessions]----------------------------------------------
PATTERN Sessions

PURPOSE CONCEPT Session IN ENGLISH
{+In order to provide ordering- and other services to clients, as well as provide order handling services to order processors, in such a way that they will only be provided services that are relevant to them, we need to distinguish the timeframes within which a specific actor interacts with the webshop. We use the term 'Session' to refer to such timeframes.-}
CONCEPT Session "a timeframe within which a specific actor interacts with the webshop" ""

PURPOSE RELATION sProvider IN ENGLISH
{+In order to know which services may be executed in a session, it is necessary to know which, if any, order processor is logged in.-}
sProvider :: Session * Processor [UNI] PRAGMA "" " is being run by "

PURPOSE RELATION sClient IN ENGLISH
{+In order to know which services may be executed in a session, it is necessary to know which, if any, client is logged in.-}
sClient   :: Session * Client   [UNI] PRAGMA "" " is being run by "

ENDPATTERN
--[Delivery Process]--------------------------------------
PROCESS Delivery

RULE "login": I[Session] |- (sProvider;V/\-sClient;V) \/ (sClient;V\/-sProvider;V)
PHRASE "Every session is being run by either a Processor or a Client"
ROLE Client MAINTAINS "login"
ROLE Client EDITS  sClient[Session*Client]
ROLE Processor MAINTAINS "login"
ROLE Processor EDITS sProvider[Session*Processor]

RULE "create orders": I[Order] |- cleanOrder
PHRASE IN ENGLISH "Each order that is created must have the 'cleanOrder' property."
PURPOSE RULE "create orders" IN ENGLISH
{+Orders that are created should record all information necessary for the webshop to decide whether or not to accept or reject it; i.e.: it should have the 'cleanOrder' property.-}
ROLE Client MAINTAINS "create orders"
ROLE Client EDITS  isPlacedBy[Order*Client], orderedItem[Order*Product], deliveryAddress, invoiceAddress

RULE "accept orders": cleanOrder |- (acceptedBy \/ rejectedBy); V
PHRASE IN ENGLISH "For every (clean) order, it must be decided whether to accept or reject it."
PURPOSE RULE "accept orders" IN ENGLISH
{+Orders are issued by clients for the purpose of making a sales transaction.
At some point in time, a order processor must accept or reject the order.-}
ROLE Processor MAINTAINS "accept orders"
ROLE Processor EDITS acceptedBy,rejectedBy

RULE "pick orders": acceptedBy |- correspondsTo[Delivery*Order]~; readyForShipping; V
PHRASE "All accepted orders should be made ready for shipping"
PURPOSE RULE "pick orders" IN ENGLISH
{+For every orders that is accepted, a delivery should be assembled consisting of all ordered products. The order processor will be signalled of all accepted orders for which a corresponding delivery is not yet (correctly) assembled.-}
ROLE Processor MAINTAINS "pick orders"
ROLE Processor EDITS correspondsTo, containsPickedItem[Delivery*Product]

RULE "ship deliveries": readyForShipping |- (hasBeenShippedBy; acceptedBy~ /\ hasBeenShippedTo; isPlacedBy~); correspondsTo[Delivery*Order]~ 
PHRASE IN ENGLISH "Each delivery  that is ready for shipping shall be sent to the client that placed the corresponding order."
PURPOSE RULE "ship deliveries" IN ENGLISH
{+When a delivery is ready for shipping, it must be sent to the client that has ordered it. The order processor will be signalled of deliveries that await shipping.-}
ROLE Processor MAINTAINS "ship deliveries"
ROLE Processor EDITS hasBeenShippedBy, hasBeenShippedTo

RULE "invoice deliveries": readyForShipping |- covers~; (hasBeenSentBy; acceptedBy~ /\ hasBeenSentTo; isPlacedBy~); correspondsTo[Delivery*Order]~
PHRASE IN ENGLISH "For each delivery that is ready for shipping, an invoice covering this delivery shall be sent to the client that placed the corresponding order."
PURPOSE RULE "invoice deliveries" IN ENGLISH
{+When a delivery is ready for shipping, an invoice for it should be sent to the client that has ordered it. The order processor will be signalled of deliveries for which an invoice needs to be sent.-}
ROLE Processor MAINTAINS "invoice deliveries"
ROLE Processor EDITS hasBeenSentBy, hasBeenSentTo

RULE "pay invoices": hasBeenSentTo |- hasPaid~
PHRASE IN ENGLISH "All invoices sent to a customer must be paid by that customer."
PURPOSE RULE "pay invoices" IN ENGLISH
{+A client to which an invoice has been sent, should pay this. The order processor will be signalled all invoices for which payment has not yet been received.-}
ROLE Processor MAINTAINS "pay invoices"
ROLE Client MAINTAINS "pay invoices"
ROLE Client EDITS hasPaid

ENDPROCESS
--[Services]----------------------------------------------
INTERFACE Login(sClient,sProvider) : I /\ -(sClient;sClient~ \/ sProvider;sProvider~)
 = [ "Login as Processor"  : sProvider[Session*Processor]
   , "or login as Client" : sClient[Session*Client]
   ]

--!t.b.v.  RULE "order creation": I[Order] |- isPlacedBy; isPlacedBy~
INTERFACE Orders(isPlacedBy[Order*Client],orderProcessor,orderedItem[Order*Product]) : (I /\ sClient;sClient~);V;(I[Order] /\ -(isPlacedBy[Order*Client];isPlacedBy[Order*Client]~))
 = [ "Client"          : isPlacedBy[Order*Client]
   , "Processor"  : orderProcessor[Order*Processor]
   , "Items"           : orderedItem[Order*Product]
   ]

--!t.b.v.  RULE "accept orders": orderProcessor~ |- acceptedBy~\/rejectedBy~   (door Processor)
INTERFACE Orders(acceptedBy,rejectedBy) : sProvider;orderProcessor~;(I[Order] /\ -(acceptedBy \/ rejectedBy);V)
 = [ "Client"          : isPlacedBy[Order*Client]
   , "Items"           : orderedItem[Order*Product]
   , "Accepted by"     : acceptedBy[Order*Processor]
   , "Rejected by"     : rejectedBy[Order*Processor]
   ]

--!t.b.v.  RULE "ship orders": acceptedBy~ |- hasBeenShippedBy~;correspondsTo   (door Processor)
INTERFACE Deliveries(correspondsTo[Delivery*Order]) : sProvider;acceptedBy[Order*Processor]~;(I /\ -correspondsTo[Delivery*Order]~;V)
 = [ "Delivery"      : correspondsTo[Delivery*Order]~
   , "Client"        : isPlacedBy[Order*Client]
   , "Items"         : orderedItem[Order*Product]
   ]

--!t.b.v.  RULE "send invoices": covers;correspondsTo;hasBeenSentBy |- hasBeenSentTo
INTERFACE SendInvoice(hasBeenSentTo[Invoice*Client],covers[Invoice*Delivery],hasBeenSentBy[Invoice*Processor]) : sProvider;acceptedBy[Order*Processor]~;correspondsTo[Delivery*Order]~;(I[Delivery] /\ -(covers~;(I[Invoice]/\hasBeenSentTo;hasBeenSentTo~ /\hasBeenSentBy[Invoice*Processor];hasBeenSentBy[Invoice*Processor]~));covers)
 = [ "invoice"  : covers[Invoice*Delivery]~
   = [ "Sent to" : hasBeenSentTo[Invoice*Client]
     , "Sent by" : hasBeenSentBy[Invoice*Processor]
   ] ]

--!t.b.v.  RULE "pay invoices": hasBeenSentTo~ |- hasPaid
INTERFACE PayInvoice(hasPaid[Client*Invoice]) : sClient;(isPlacedBy[Order*Client]~;correspondsTo[Delivery*Order]~;covers[Invoice*Delivery]~/\-hasPaid[Client*Invoice])
 = [ "Delivery"      : covers[Invoice*Delivery]
   = [ "Order"       : correspondsTo[Delivery*Order]
     = [ "Items"       : orderedItem[Order*Product]
     ] ]
   , "paid by"       : hasPaid[Client*Invoice]~
   ]

--!t.b.v.  RULE "receive goods": correspondsTo[Delivery*Order];isPlacedBy[Order*Client] |- hasBeenShippedTo[Delivery*Client]
INTERFACE SignDeliveryReceipt(hasBeenShippedTo) : I[Delivery]
 = [ "Order"         : correspondsTo[Delivery*Order]
   = [ "Client"        : isPlacedBy[Order*Client]
     , "Items"         : orderedItem[Order*Product]
     ]
   , "Delivered items" : containsPickedItem[Delivery*Product]
   ]

-- hasBeenShippedTo :: Delivery -> Client PRAGMA "" " is delivered to "

--[Miscellaneous Populations]------------------------------

CONCEPT Client "an entity that can place orders, receive deliveries and pay invoices for ordered products" ""
PURPOSE CONCEPT Client IN ENGLISH
{+The purpose of a webshop is to sell products. The term 'client' is used to designate parties to whom such products are being sold.-}

CONCEPT Address "an identifier of a location to which deliveries can be shipped and invoices can be sent" ""
PURPOSE CONCEPT Address IN ENGLISH
{+In order to send deliveries or invoices, the destination location must be known. We use the term 'address' for identifiers of such locations.-}

CONCEPT Order "a set of products that a clients wishes the webshop to deliver" ""
PURPOSE CONCEPT Order IN ENGLISH
{+In order to sell products, the webshop must allow clients to specify the products they wish to be delivered. The term 'order' refers to a set of products that a client has specified for delivery.-}

CONCEPT Product "an item that the webshop is capable of selling" ""
PURPOSE CONCEPT Product IN ENGLISH
{+In order for a webshop to sell items, it should have items to sell. The term 'Product' refers to items that can be sold.-}

CONCEPT Processor "an actor that is capable of handling orders" ""
PURPOSE CONCEPT Processor IN ENGLISH
{+Processing orders consists of accepting or rejecting them, picking them so as to assemble a delivery, etcetra. The term 'Processor' refers to a person that performs such tasks.-}

CONCEPT Delivery "a set of picked (physical) items that correspond to the items listed on a single order" ""
PURPOSE CONCEPT Delivery IN ENGLISH
{+After an order has been accepted, the items on the order must be picked to form what is called a 'Delivery', which can subsequently be sent to the client that placed the order.-}

CONCEPT Invoice "a specification of the amount a client is expected to pay for the delivered products corresponding to a specific order" ""
PURPOSE CONCEPT Invoice IN ENGLISH
{+To enable a client to pay for the products in a delivery, the client must be informed about the costs thereof. We use the term 'Invoice' to refer to a message that spells out what the client is expected to pay for the products that the client ordered.-}
