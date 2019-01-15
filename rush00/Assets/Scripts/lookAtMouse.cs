using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class lookAtMouse : MonoBehaviour {

	Vector3 mousePosition;

	// Update is called once per frame
	void Update () {        
        mousePosition = Camera.main.ScreenToWorldPoint(Input.mousePosition);
 
        Quaternion rot = Quaternion.LookRotation(transform.position - mousePosition, Vector3.back );
        transform.rotation = rot;
        transform.eulerAngles = new Vector3(0, 0,transform.eulerAngles.z);
    }
}
