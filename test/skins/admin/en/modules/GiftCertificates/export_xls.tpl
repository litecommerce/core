   <Row IF="!order.payedByGC=0">
    <Cell><Data ss:Type="String">Paid with GC # {order.gcid}:</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.payedByGC}</Data></Cell>
   </Row>

