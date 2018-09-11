import React, { StatelessComponent } from 'react';

export interface IMathTeaserFragment {
  __typename: string;
  title: string;
  fieldMathField: string;
  fieldMathFieldCalculated: string;
}

// tslint:disable-next-line:no-empty-interface
export interface IMathTeaserProps extends IMathTeaserFragment {}

const MathTeaser: StatelessComponent<IMathTeaserProps> = ({
  title,
  fieldMathField,
  fieldMathFieldCalculated,
}) => (
  <div className="Wrapper">
    <h1>{title}</h1>
    <div className="results">
      {fieldMathField} = {fieldMathFieldCalculated}
    </div>
  </div>
);

export default MathTeaser;
