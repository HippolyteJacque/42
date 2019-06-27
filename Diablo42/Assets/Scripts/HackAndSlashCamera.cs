using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class HackAndSlashCamera : MonoBehaviour {

	public Transform target;
	public float distance;
	public float height;

	private Transform _myTransform;

	void Start(){
		if (target == null)
			Debug.LogWarning("We do not have a target.");
	
		_myTransform = transform;
	}

	void LateUpdate(){
		_myTransform.position = new Vector3(target.position.x, target.position.y + height, target.position.z - distance);
		_myTransform.LookAt(target);		
	}
}
