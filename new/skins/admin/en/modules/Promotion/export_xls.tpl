   <Row IF="!order.payedByPoints=0">
    <Cell><Data ss:Type="String">Bonus points discount:</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.payedByPoints}</Data></Cell>
   </Row>

